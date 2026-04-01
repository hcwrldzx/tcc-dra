/**
 * MOCK BACKEND API
 * Simula um banco de dados e rotas de backend usando o LocalStorage do navegador.
 * Mantém tudo separado da UI como um bom projeto de software!
 */

const Api = {
    // ---- BANCO DE DADOS (Inicialização) ----
    initDB: function() {
        if (!localStorage.getItem('appointments')) {
            localStorage.setItem('appointments', JSON.stringify([]));
        }
        if (!localStorage.getItem('patients')) {
            localStorage.setItem('patients', JSON.stringify([]));
        }
        if (!localStorage.getItem('settings')) {
            localStorage.setItem('settings', JSON.stringify({ taxRate: 0.15 })); // 15% de taxas sobre o bruto
        }
    },

    // ---- AUTENTICAÇÃO ----
    login: async function(email, password) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Credenciais Fixas para teste da Dra.
                if (email === 'afaodontologia1@gmail.com' && password === 'admin123') {
                    localStorage.setItem('auth_token', 'token_seguro_da_dra');
                    resolve({ success: true, user: { name: 'AFA Odontologia', role: 'admin' } });
                } else {
                    resolve({ success: false, message: 'E-mail ou senha incorretos' });
                }
            }, 800); // delay fingindo internet
        });
    },

    logout: function() {
        localStorage.removeItem('auth_token');
    },

    isAuthenticated: function() {
        return !!localStorage.getItem('auth_token');
    },

    // ---- AGENDAMENTOS ----
    getAppointments: async function() {
        return new Promise(resolve => {
            const data = JSON.parse(localStorage.getItem('appointments'));
            resolve(data.sort((a,b) => new Date(b.createdAt) - new Date(a.createdAt)));
        });
    },

    addAppointment: async function(appointmentData) {
        return new Promise(resolve => {
            setTimeout(() => {
                const appointments = JSON.parse(localStorage.getItem('appointments'));
                
                const newAppt = {
                    id: 'ODP' + Date.now().toString().slice(-4),
                    ...appointmentData,
                    status: 'pendente', // pendente, confirmada, cancelada, concluida
                    createdAt: new Date().toISOString()
                };
                
                appointments.push(newAppt);
                localStorage.setItem('appointments', JSON.stringify(appointments));
                
                // Salvar o paciente também para histórico (Simplificado)
                this._savePatient(appointmentData.patient);

                resolve({ success: true, id: newAppt.id });
            }, 1000);
        });
    },

    updateAppointmentStatus: async function(id, newStatus) {
        return new Promise(resolve => {
            const appointments = JSON.parse(localStorage.getItem('appointments'));
            const idx = appointments.findIndex(a => a.id === id);
            
            if (idx > -1) {
                appointments[idx].status = newStatus;
                if (newStatus === 'remarcada' || newStatus === 'concluida') {
                    appointments[idx].updatedAt = new Date().toISOString();
                }
                localStorage.setItem('appointments', JSON.stringify(appointments));
                resolve({ success: true });
            } else {
                resolve({ success: false, message: 'Consulta não encontrada' });
            }
        });
    },

    // ---- HISTÓRICO DE PACIENTES ----
    getPatients: async function() {
        return new Promise(resolve => resolve(JSON.parse(localStorage.getItem('patients'))));
    },

    _savePatient: function(patientData) {
        const patients = JSON.parse(localStorage.getItem('patients'));
        const existing = patients.find(p => p.email === patientData.email || p.phone === patientData.phone);
        
        if (!existing) {
            patients.push({
                ...patientData,
                id: 'PAC' + Date.now().toString().slice(-4),
                registeredAt: new Date().toISOString()
            });
            localStorage.setItem('patients', JSON.stringify(patients));
        }
    },

    // ---- FINANCEIRO ----
    getFinancialReport: async function() {
        return new Promise(resolve => {
            const appointments = JSON.parse(localStorage.getItem('appointments'));
            const settings = JSON.parse(localStorage.getItem('settings'));
            
            // Apenas para métricas simplificadas (ignorando datas profundas)
            // Considerar receita as consultas confirmadas e concluidas
            const validAppointments = appointments.filter(a => a.status === 'confirmada' || a.status === 'concluida');
            
            const grossRevenue = validAppointments.reduce((acc, curr) => acc + curr.service.price, 0);
            const netRevenue = grossRevenue - (grossRevenue * settings.taxRate);
            
            // Dividir por Status
            const countPending = appointments.filter(a => a.status === 'pendente').length;
            const countConfirmed = validAppointments.length;
            const countCancelled = appointments.filter(a => a.status === 'cancelada').length;

            resolve({
                gross: grossRevenue,
                net: netRevenue,
                taxesDeducted: grossRevenue * settings.taxRate,
                taxRate: settings.taxRate,
                stats: { pending: countPending, confirmed: countConfirmed, cancelled: countCancelled },
                history: validAppointments // para gerar gráficos futuramente
            });
        });
    }
};

// Auto inicializa o banco quando a página carrega
Api.initDB();
