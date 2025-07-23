#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
RESET='\033[0m'

colors=(
  "$RED"
  "$GREEN"
  "$YELLOW"
  "$BLUE"
  "$MAGENTA"
  "$CYAN"
)

random_index=$(( RANDOM % ${#colors[@]} ))
selected_color="${colors[random_index]}"

RIGHT_ARROW='\u27A1'
WHITE_CHECK='\u2705'