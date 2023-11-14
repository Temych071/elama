## Install

`ansible-galaxy install -r requirements.yml`

## Server setup

`ansible-playbook server-setup.yml -v -i inventory  --ask-vault-pass -e @vars.yml`
