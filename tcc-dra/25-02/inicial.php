<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Clínica - Clínica Odontológica de Referência</title>
    <link rel="stylesheet" href="css/inicial.css">
    <link rel="shortcut icon" href="favicondentalsoft.png"  class="favicon" type="image/x-icon">
</head>
<body>
    <!-- Header / Navbar -->
    <header class="header">
        <div class="container">
            <div class="logo">
               <img src="dentalsoft.png" alt="Dental Soft" class="logo-img" title="Dental Soft">
                <h1>Dental Soft</h1>
            </div>
            
            <nav class="navbar">
                <a href="#home" class="nav-link">HOME</a>
                <a href="#clinica" class="nav-link">CLÍNICA</a>
                <a href="#servicos" class="nav-link">SERVIÇOS</a>
                <a href="#contato" class="nav-link">CONTATO</a>
                <a href="admin_login.php" class="nav-link" style="color: #0ea5e9; font-weight: 600;">ADMIN</a>
            </nav>

            <a href="agendar.php" class="btn-agendar-header">AGENDAR CONSULTA</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container hero-content">
            <div class="hero-text">
                <h2 class="hero-title">Clínica Odontológica de Referência</h2>
                <p class="hero-subtitle">Odontologia geral, estética e ortodontia</p>
                <a href="agendar.php" class="btn-agendar-hero">Agendar Consulta</a>
            </div>
            
            <div class="hero-image">
                <img src="" alt="Clínica AFA"class="hero-img"> 
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="clinica">
        <div class="container">
            <div class="about-content">
                <div class="about-text-left">
                    <div class="about-decorative about-deco-1"></div>
                    <div class="about-decorative about-deco-2"></div>
                    <h2>Atendimento odontológico para toda a família</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <ul class="about-list">
                        <li><span class="checkmark">✓</span> Odontologia Geral</li>
                        <li><span class="checkmark">✓</span> Odontopediatria</li>
                        <li><span class="checkmark">✓</span> Ortodontia</li>
                    </ul>
                </div>
                
                <div class="about-text-right">
                    <h3>Cuidamos de toda sua saúde bucal</h3>
                    <p>Atendemos desde procedimentos mais simples como profilaxia, até cirurgias, implantes e tratamentos complexos. Você pode confiar, que nossos especialistas vão cuidar muito bem de você e da sua família.</p>
                    <a href="agendar.php" class="btn-agendar-about">Agendar Consulta</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="servicos">
        <div class="container">
            <h2 class="services-title">Serviços realizados</h2>
        </div>
        <div class="services-icons-bar">
            <div class="services-icon-item">
                <img src="images/icone1.svg" alt="Odontologia Geral">
            </div>
            <div class="services-icon-item">
                <img src="images/icone2.svg" alt="Implantes">
            </div>
            <div class="services-icon-item">
                <img src="images/icone3.svg" alt="Restaurações">
            </div>
            <div class="services-icon-item">
                <img src="images/icone4.svg" alt="Estética">
            </div>
            <div class="services-icon-item">
                <img src="images/icone5.svg" alt="Clareamento">
            </div>
            <div class="services-icon-item">
                <img src="images/icone6.svg" alt="Ortodontia">
            </div>
        </div>
        <div class="services-content">
            <div class="services-grid">
                <div class="service-card">
                    <h3>Odontologia<br>Geral</h3>
                    <a href="#" class="service-link">Saiba Mais</a>
                </div>
                
                <div class="service-card">
                    <h3>Implantes<br>dentários</h3>
                    <a href="#" class="service-link">Saiba Mais</a>
                </div>
                
                <div class="service-card">
                    <h3>Restaurações</h3>
                    <a href="#" class="service-link">Saiba Mais</a>
                </div>
                
                <div class="service-card">
                    <h3>Odontologia<br>Estética</h3>
                    <a href="#" class="service-link">Saiba Mais</a>
                </div>
                
                <div class="service-card">
                    <h3>Clareamento<br>dental</h3>
                    <a href="#" class="service-link">Saiba Mais</a>
                </div>
                
                <div class="service-card">
                    <h3>Ortodontia</h3>
                    <a href="#" class="service-link">Saiba Mais</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contato">
        <div class="container">
            <h2 class="contact-title">Entre em Contato</h2>
            
            <!-- Formulário e Horários -->
            <div class="contact-top">
                <div class="contact-form">
                    <h3>Envie uma mensagem<br>ou Agende sua consulta online</h3>
                    <form>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" placeholder="Nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="mensagem">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" rows="4" placeholder="Mensagem" required></textarea>
                        </div>
                        <button type="submit" class="btn-enviar">Enviar mensagem</button>
                    </form>
                </div>

                <div class="contact-hours">
                    <h3>Horários de atendimento:</h3>
                    <div class="hours-list">
                        <p>Segunda: 08:00 – 20:00</p>
                        <p>Terça: 08:00 – 20:00</p>
                        <p>Quarta: 08:00 – 20:00</p>
                        <p>Quinta: 08:00 – 20:00</p>
                        <p>Sexta: 08:00 – 20:00</p>
                        <p>Sábado: 08:00 – 12:00</p>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="contact-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.193768547865!2d-46.64382562346916!3d-23.588844263531466!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c5c8f8c0c0c0c1%3A0x0!2sRua%20das%20Flores%2C%201250%20-%20Vila%20Mariana%2C%20S%C3%A3o%20Paulo%20-%20SP!5e0!3m2!1spt-BR!2sbr!4v1234567890" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <!-- Informações de Contato -->
            <div class="contact-info">
                <div class="info-item">
                    <h4>📍 Localização</h4>
                    <p>Rua das Flores, 1250<br>Vila Mariana<br>São Paulo - SP, 04016-032</p>
                </div>

                <div class="info-item">
                    <h4>📞 Telefone</h4>
                    <p>(11) 3845-2910<br>(11) 99876-5432</p>
                </div>

                <div class="info-item">
                    <h4>📧 Email</h4>
                    <p>contato@dentalsoft.com.br<br>agendamento@dentalsoft.com.br</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Dental Soft</h4>
                    <p>Clínica odontológica de referência em atendimento de qualidade.</p>
                </div>
                <div class="footer-section">
                    <h4>Horário</h4>
                    <p>Segunda a Sexta: 9h às 18h<br>Sábado: 9h às 13h</p>
                </div>
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p>Telefone: (XX) XXXX-XXXX<br>Email: contato@dentalsoft.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Dental Soft. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
