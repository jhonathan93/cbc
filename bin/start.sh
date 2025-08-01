#!/bin/bash

# shellcheck disable=SC2154

source ./.docker/script/colors.sh

detect_docker_compose() {
  if command -v docker-compose >/dev/null 2>&1; then
    echo "docker-compose"
  elif docker compose version >/dev/null 2>&1; then
    echo "docker compose"
  else
    echo "❌ Erro: Docker Compose não encontrado. Instale o Docker Compose." >&2
    exit 1
  fi
}

DOCKER_COMPOSE_CMD=$(detect_docker_compose)

echo -e "${GREEN}ℹ️  Usando comando: ${DOCKER_COMPOSE_CMD}${RESET}"

$DOCKER_COMPOSE_CMD up -d

echo -e "\n"
echo -e "${GREEN}${WHITE_CHECK} Configuração concluída! Todos os serviços foram iniciados com sucesso!${RESET}"
echo -e "\n"

source ./.docker/script/signature.sh