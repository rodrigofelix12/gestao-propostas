# API de Gestão de Propostas

API versionada em `/api/v1` para gerenciar clientes e propostas, com auditoria, validação de status, idempotência e busca avançada.

---

## 1. Setup do Projeto

1. Clonar o repositório:
```bash
git clone <repo-url>
cd <projeto>
```
2. Instalar Dependências:
```bash
composer install
```
3. Copiar Arquivo de ambiente:
```bash
cp env .env
```
4. Configurar .env:
```bash
CI_ENVIRONMENT = development
database.default.DBDriver = SQLite3
database.default.database = /caminho/absoluto/para/writable/database.db
database.default.hostname =
database.default.username =
database.default.password =
database.default.port =
```
5. Criar arquivo SQLite vazio (se ainda não existir):
```bash
touch writable/database.db
chmod 777 writable/database.db
```
6. Rodar Migrations:
```bash
php spark migrate
```
7. Rodar Seeds:
```bash
php spark db:seed DatabaseSeeder
```
8. Iniciar Servidor:
```bash
php spark serve
```

## 2. Endpoints da API
Todos os endpoints são versionados em /api/v1.

Clientes
| Método | Endpoint         | Descrição                     |
| ------ | ---------------- | ----------------------------- |
| POST   | `/clientes`      | Criar cliente                 |
| GET    | `/clientes/{id}` | Buscar cliente por ID         |
| GET    | `/clientes`      | Listar clientes com paginação |
| PUT    | `/clientes/{id}` | Atualizar cliente             |
| DELETE | `/clientes/{id}` | Remover cliente               |

Exemplo de request para criar cliente:
```bash
POST /api/v1/clientes
{
  "nome": "João Silva",
  "email": "joao@example.com",
  "documento": "99999999999"
}
```

Propostas
| Método | Endpoint                    | Descrição                                |
| ------ | --------------------------- | ---------------------------------------- |
| POST   | `/propostas`                | Criar proposta                           |
| PATCH  | `/propostas/{id}`           | Atualizar campos da proposta             |
| POST   | `/propostas/{id}/submit`    | Enviar proposta                          |
| POST   | `/propostas/{id}/approve`   | Aprovar proposta                         |
| POST   | `/propostas/{id}/reject`    | Rejeitar proposta                        |
| POST   | `/propostas/{id}/cancel`    | Cancelar proposta                        |
| GET    | `/propostas/{id}`           | Buscar proposta por ID                   |
| GET    | `/propostas`                | Listar propostas com filtros e paginação |
| GET    | `/propostas/{id}/auditoria` | Buscar auditoria de proposta             |

Exemplo de request para criar proposta:
```bash
POST /api/v1/propostas
{
  "cliente_id": 1,
  "produto": "Seguro Vida",
  "valor_mensal": 150.00,
  "origem": "APP"
}
```

Exemplo de busca com filtros:
```bash
GET /api/v1/propostas?status=DRAFT&cliente_id=1&page=1&per_page=10
```

Filtros disponíveis:

* status → DRAFT, SUBMITTED, APPROVED, REJECTED, CANCELED

* cliente_id → ID do cliente

* origem → APP, SITE, API

* data_inicio → YYYY-MM-DD

* data_fim → YYYY-MM-DD

* page → número da página

* per_page → itens por página

Auditoria de Propostas
| Método | Endpoint                    | Descrição                          |
| ------ | --------------------------- | ---------------------------------- |
| GET    | `/propostas/{id}/auditoria` | Lista de auditoria de uma proposta |

Exemplo de response:
```bash
[
  {
    "id": 1,
    "proposta_id": 1,
    "actor": "system",
    "evento": "CREATED",
    "payload": "{\"after\":{\"id\":1,\"status\":\"DRAFT\"}}",
    "created_at": "2026-02-11 20:46:57"
  },
  {
    "id": 2,
    "proposta_id": 1,
    "actor": "system",
    "evento": "STATUS_CHANGED",
    "payload": "{\"from\":\"DRAFT\",\"to\":\"SUBMITTED\"}",
    "created_at": "2026-02-11 21:00:00"
  }
]
```