# Desafio técnico &middot; Onfly
> Este repositório contém uma API desenvolvida em Laravel para gestão de pedidos de viagem corporativa. O sistema permite que usuários criem, editem e visualizem pedidos de viagem, enquanto administradores podem aprovar ou cancelar pedidos.

A infraestrutura do projeto é gerenciada por Docker, o que facilita a configuração de um ambiente consistente e pronto para uso.

## Informações do projeto

### Tecnologias Utilizadas
**Backend**

- Laravel 11: Framework PHP usado para construir a API RESTful do sistema.
- PHP 8.2
- Mensageria com Laravel Queues
- MySQL: Banco de dados relacional para armazenar os dados.
- Autenticação via JWT
- Laravel Telescope: Um painel de monitoramento para Laravel. Ele permite visualizar requisições HTTP, exceções, jobs em fila, consultas SQL, logs, eventos e muito mais em tempo real.

**Infraestrutura - Docker**

- Docker: Utilizado para encapsular todo o projeto em containers, garantindo que o ambiente de desenvolvimento seja idêntico ao de produção.
- Docker Compose: Orquestra os containers do projeto, incluindo PHP, Nginx, MySql e o Mailhog.
- Mailhog: Utilizado para testes de envio de e-mail.


### Arquitetura

O projeto é baseado na estrutura padrão do Laravel, porém com uma separação clara de responsabilidades para garantir maior manutenibilidade e testabilidade. Os principais conceitos aplicados são:
- **Camada de Apresentação (Controllers)**: Gerencia as requisições HTTP e validações iniciais.
- **Camada de Aplicação (Services)**: Contém a lógica de negócios centralizada.
- **Camada de Infraestrutura (Repositories, Notifications, Jobs)**: Separa a comunicação com o banco de dados e serviços externos.
- **Camada de Domínio (Models, Requests)**: Define entidades e validação.

### Design Patterns Utilizados

- **Repository Pattern**: Utilizado para abstrair a lógica de acesso ao banco de dados, facilitando a troca do ORM caso necessário e garantindo que as queries fiquem isoladas.
- **Service Layer (Application Services)**: Utilizado Services para isolar a lógica de negócio, evitando sobrecarregar os Controllers.
- **Factory Pattern**: Usado para criar dados de teste e facilitar a geração de instâncias realistas nos testes.
- **Queue/Job Pattern**: Utilizado Jobs para processamento assíncrono das notificações.

## Getting Started

### Dependências
Para executar este projeto, você precisará do [Docker](https://www.docker.com/) instalado (há um arquivo docker-compose na raiz do projeto).

**Clonando o repositório**
```shell
$ git clone https://github.com/ph-gaia/onfly-challenge.git

$ cd onfly-challenge
```

**Copiar o Arquivo de Configuração**
```
cp .env.example .env
```

**Inicie os containers Docker:**
```
$ docker-compose up --build
```

**Gerar Chave da Aplicação:**
```
docker-compose exec app php artisan key:generate
```

**Configurar o Banco de Dados:**
```
docker-compose exec app php artisan migrate
```

**Fila e Notificação de Pedidos**
```
docker-compose exec app php artisan queue:work
```

**Acesse a aplicação:**

A API estará disponível em http://localhost:8080/api

**Monitoramento**

Este projeto utiliza o Laravel Telescope para monitoramento e depuração de requisições, eventos, jobs e exceções.
A interface pode ser acessada em:

http://localhost:8000/telescope

## Endpoints da API

A API permite operações básicas para gerenciar os pedidos.
> Mais detalhes dos endpoints você pode ver a documentação completa no microserviço [aqui](https://github.com/ph-gaia/onfly-challenge/blob/main/onfly-app/README.md)

- POST /api/login: Autenticação
- POST /api/register: Registrar novo usuário
- POST /api/travel-orders: Criar um novo pedido
- GET /api/travel-orders: Listar todos os pedidos
- GET /api/travel-orders/{id}: Consultar um pedido específico
- PATCH /api/travel-orders/{id}/status: Atualizar o status de um pedido


## Author
- [Paulo Henrique Coelho Gaia](https://www.linkedin.com/in/ph-gaia)