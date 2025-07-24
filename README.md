# 🚀 Instalação com Docker Compose

Como configurar e executar a aplicação e o banco de dados MySQL usando Docker Compose.

---

## ✅ Pré-requisitos

Antes de começar, certifique-se de que os seguintes itens estejam instalados:

- Docker
- Docker Compose

---

## ⚙️ Como executar

No diretório raiz do projeto, execute o seguinte comando para iniciar os containers:

```bash
  docker-compose build --no-cache && docker-compose up -d
```
ou 
```bash
  ./bin/start.sh
```

Qualquer um dos comandos iniciará a criação dos containers e servidor

## Orientações 

Todas as configurações necessárias para rodar o servidor de forma perfeita serão feitas automaticamente pelo docker, caso venha acontecer algum problema, os arquivos importantes estão todos no repositório para realizar a configuração manualmente.