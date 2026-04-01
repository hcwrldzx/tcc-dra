/**
 * FRONTEND MVC LOGIC (Multi-page Architecture)
 * Lida de forma segura com diferentes páginas
 */

const App = {
    state: {
        sched: { service: '', price: 0, date: '', time: '' }
    },

    init: function() {
        if(document.getElementById('sel-date')) {
            this.setupDateLimit();
        }
        
        // Se estamos na pagina admin, checar auth
        if(document.getElementById('admin-login') || document.getElementById('admin-dashboard')) {
            this.checkAuth();
        }
    },

    // ---- FLUXO DE AGENDAMENTO (PACIENTES) ----
    openModal: function(id) {
        const m = document.getElementById(id);
        if(m) m.classList.remove('hidden');
    },

    closeModal: function(id) {
        const m = document.getElementById(id);
        if(m) m.classList.add('hidden');
        
        const step1 = document.getElementById('sched-step-1');
        const step2 = document.getElementById('sched-step-2');
        const step3 = document.getElementById('sched-step-3');
        
        if(step1) step1.classList.remove('hidden');
        if(step2) step2.classList.add('hidden');
        if(step3) step3.classList.add('hidden');
        
        this.state.sched = {};
        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
    },

    setupDateLimit: function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('sel-date').setAttribute('min', today);
    },

    selectSlot: function(btnElement) {
        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
        btnElement.classList.add('selected');
        document.getElementById('sel-time').value = btnElement.innerText;
    },

    nextSchedStep: function() {
        const selVal = document.getElementById('sel-service').value.split('|');
        const selDate = document.getElementById('sel-date').value;
        const selTime = document.getElementById('sel-time').value;

        if (!selDate || !selTime) {
            alert("Por favor, escolha uma data e um horário livre.");
            return;
        }

        this.state.sched.service = { name: selVal[0], price: parseFloat(selVal[1]) };
        const d = selDate.split('-');
        this.state.sched.date = `${d[2]}/${d[1]}/${d[0]}`;
        this.state.sched.time = selTime;

        document.getElementById('sched-step-1').classList.add('hidden');
        document.getElementById('sched-step-2').classList.remove('hidden');
    },

    prevSchedStep: function() {
        document.getElementById('sched-step-2').classList.add('hidden');
        document.getElementById('sched-step-1').classList.remove('hidden');
    },

    finishScheduling: async function() {
        const name = document.getElementById('pat-name').value;
        const phone = document.getElementById('pat-phone').value;
        const email = document.getElementById('pat-email').value;

        if (!name || !phone || !email) {
            alert('Preencha todos os dados obrigatórios (*).');
            return;
        }

        const btn = document.getElementById('btn-finish-ag');
        btn.innerText = 'Processando...';
        btn.disabled = true;

        const data = {
            service: this.state.sched.service,
            date: this.state.sched.date,
            time: this.state.sched.time,
            patient: { name, phone, email }
        };

        const res = await Api.addAppointment(data);
        
        if (res.success) {
            document.getElementById('sched-step-2').classList.add('hidden');
            document.getElementById('sched-step-3').classList.remove('hidden');
        } else {
            alert('Falha ao agendar: ' + res.message);
        }

        btn.innerText = 'Finalizar Agendamento';
        btn.disabled = false;
    },

    simularEnvioWhatsappPaciente: function() {
        const msg = `Olá, gostaria de confirmar meu agendamento de ${this.state.sched.service.name} para o dia ${this.state.sched.date} às ${this.state.sched.time}. Meu nome é ${document.getElementById('pat-name').value} e meu telefone é ${document.getElementById('pat-phone').value}.`;
        const clinicPhone = '11983719203'; 
        window.open(`https://wa.me/55${clinicPhone}?text=${encodeURIComponent(msg)}`, '_blank');
    },

    // ---- FLUXO DA DRA. (ADMINISTRAÇÃO/DASHBOARD) ----
    checkAuth: function() {
        const dash = document.getElementById('admin-dashboard');
        const login = document.getElementById('admin-login');
        
        if(!dash || !login) return; // Não está na pagina admin

        if (Api.isAuthenticated()) {
            login.classList.add('hidden');
            dash.classList.remove('hidden');
            this.loadAdminData();
        } else {
            login.classList.remove('hidden');
            dash.classList.add('hidden');
        }
    },

    handleLogin: async function(e) {
        e.preventDefault(); 
        const em = document.getElementById('login-email').value;
        const pw = document.getElementById('login-pass').value;
        const btn = document.getElementById('btn-login-submit');
        const err = document.getElementById('login-error');
        
        err.classList.add('hidden');
        btn.innerText = "Verificando...";
        
        const res = await Api.login(em, pw);
        
        if (res.success) {
            document.getElementById('admin-user-name').innerText = `Olá, ${res.user.name}`;
            this.checkAuth();
        } else {
            err.innerText = res.message;
            err.classList.remove('hidden');
        }
        
        btn.innerText = "Entrar no Sistema";
    },

    logout: function() {
        Api.logout();
        this.checkAuth();
        const p = document.getElementById('login-pass');
        if(p) p.value = '';
    },

    loadAdminData: async function() {
        const dashGross = document.getElementById('dash-gross');
        if(!dashGross) return; // Segurança caso a DOM suma

        const finances = await Api.getFinancialReport();
        dashGross.innerText = `R$ ${finances.gross.toFixed(2).replace('.', ',')}`;
        document.getElementById('dash-net').innerText = `R$ ${finances.net.toFixed(2).replace('.', ',')}`;
        
        const apps = await Api.getAppointments();
        document.getElementById('dash-count').innerText = apps.length;
        
        const list = document.getElementById('admin-appointments-list');
        const empty = document.getElementById('admin-empty-state');
        list.innerHTML = '';
        
        if (apps.length === 0) {
            empty.classList.remove('hidden');
            list.parentElement.classList.add('hidden');
        } else {
            empty.classList.add('hidden');
            list.parentElement.classList.remove('hidden');
            
            apps.forEach(app => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${app.patient.name}</strong></td>
                    <td class="text-sm">
                        <a href="https://wa.me/55${app.patient.phone.replace(/\D/g,'')}?text=Olá, somos da Clínica!" target="_blank" class="text-success" title="Chamar Whatsapp"><i class="ri-whatsapp-line text-lg"></i></a>
                        <br>${app.patient.phone}
                    </td>
                    <td>${app.service.name}<br><strong class="text-gray">R$ ${app.service.price.toFixed(2)}</strong></td>
                    <td>${app.date}<br><strong>${app.time}</strong></td>
                    <td><span class="status-badge status-${app.status}">${app.status.toUpperCase()}</span></td>
                    <td>
                        <button class="btn btn-sm btn-secondary mb-2 bg-light text-success w-full" style="border-color: var(--success);" onclick="App.changeStatus('${app.id}', 'confirmada')">Confirmar</button>
                        <button class="btn btn-sm btn-secondary mb-2 w-full text-danger" style="border-color: var(--danger)" onclick="App.changeStatus('${app.id}', 'cancelada')">Cancelar</button>
                        <button class="btn btn-sm bg-dark text-white w-full" onclick="App.changeStatus('${app.id}', 'concluida')">Marcar Paga</button>
                    </td>
                `;
                list.appendChild(tr);
            });
        }
    },

    changeStatus: async function(id, status) {
        if(confirm(`Tem certeza que deseja mudar o status dessa consulta para ${status.toUpperCase()}?`)) {
            const res = await Api.updateAppointmentStatus(id, status);
            if(res.success) {
                this.loadAdminData();
            }
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    App.init();
});
