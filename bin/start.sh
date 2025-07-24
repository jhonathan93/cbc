#!/bin/bash

# shellcheck disable=SC2154

source ./.docker/script/colors.sh

docker-compose build --no-cache && docker-compose up -d

echo -e "\n"
echo -e "${GREEN}${WHITE_CHECK} Configuração concluída! Todos os serviços foram iniciados com sucesso!${RESET}"
echo -e "\n"

source ./.docker/script/signature.sh