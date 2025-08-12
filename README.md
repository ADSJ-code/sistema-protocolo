# Sistema de Protocolo e Aprovação

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

## Sumário

Este projeto é uma aplicação web full-stack desenvolvida com PHP e MySQL que simula um sistema de protocolo e fluxo de trabalho interno. A aplicação valida a experiência mencionada no meu currículo, demonstrando a capacidade de construir soluções que otimizam processos burocráticos e implementam regras de negócio complexas.

## Funcionalidades Principais

* **Autenticação e Sessões:** Sistema de login seguro que gere sessões de utilizador.
* **Controlo de Acesso Baseado em Papéis (Roles):** A aplicação distingue entre dois tipos de utilizador ("Funcionário" e "Gestor"), exibindo interfaces e permissões diferentes para cada um.
* **Fluxo de Trabalho de Aprovação:** Gestores podem visualizar todos os pedidos e alterar o seu status para "Aprovado" ou "Rejeitado".
* **Histórico de Auditoria:** O sistema regista todas as alterações de status numa tabela de histórico dedicada, guardando o status anterior, o novo, quem realizou a alteração e quando. Esta informação é visível numa página de detalhes para cada pedido.
* **Deteção de Conflito de Interesse:** A interface do gestor destaca visualmente os pedidos que foram aprovados ou rejeitados pelo mesmo utilizador que os criou, demonstrando uma lógica de auditoria proativa.

## Stack de Tecnologias

* **Backend:** PHP (utilizando PDO para acesso seguro à base de dados)
* **Banco de Dados:** MySQL
* **Frontend:** HTML5, CSS3
* **Estilo Visual:** Pico.css
* **Ambiente de Desenvolvimento:** Docker (para o serviço MySQL)

## Como Rodar o Projeto

1.  **Clone o repositório** e navegue para a pasta.
2.  **Inicie o servidor MySQL** via Docker e execute o script SQL para criar a base de dados e as tabelas.
3.  **Configure as credenciais** do banco de dados no arquivo `db_connection.php`.
4.  **Inicie o servidor de desenvolvimento** do PHP a partir da pasta do projeto:
    ```bash
    php -S localhost:8000
    ```
5.  Acesse `http://localhost:8000` no seu navegador.

**Credenciais de Teste:**
* **Gestor:** `gestor@email.com` / `senha123`
* **Funcionário:** `funcionario@email.com` / `senha123`