# Micro serviço de pedidos &middot;
> Este é o repositório que contém o micro serviço desenvolvida em Laravel para gestão de pedidos de viagem corporativa. 

## Endpoints da API

A API permite operações básicas para gerenciar os pedidos.

### Autenticação (Auth)
**Login**
- POST /api/login
- body: 
```
{
  "email": "usuario@email.com",
  "password": "123456"
}
```
**Registrar novo usuário**
- POST /api/register
- body: 
```
{
  "name": "Paulo Henrique",
  "email": "paulo.henrique@onfly.com",
  "password": "123456",
  "password_confirmation": "123456"
}

```

### Pedidos de Viagem (Travel Orders)
**Criar um novo pedido**
- POST /api/travel-orders
- Requer autenticação
- body: 
```
{
  "destination": "São Paulo",
  "departure_date": "2025-02-10",
  "return_date": "2025-02-20"
}
```

**Listar todos os pedidos**
- GET /api/travel-orders
- Usuário comum ver apenas seus pedidos
- Admin ver todos os pedidos
- Query Params opcionais:
    - status (approved, canceled)
    - start_date, end_date (intervalo de datas)
    - destination

**Consultar um pedido específico**
- GET /api/travel-orders/{id}
- Usuário comum só pode acessar seus pedidos

**Atualizar o status de um pedido**
- PATCH /api/travel-orders/{id}/status
- Apenas administradores podem alterar o status
- body: 
```
{
  "status": "approved"
}
```

## Notificações (Emails e Eventos)

Sempre que um pedido for aprovado ou cancelado, a API enviará um e-mail para o solicitante informando a mudança de status.
Esse envio ocorre de forma assíncrona usando filas de Jobs.
