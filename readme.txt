# SiPPeC - Sistema de Promoção ao Pensamento Computacional

## Descrição
O SiPPeC é um sistema desenvolvido para gerenciar os catálogos do projeto ACT (Aprendizado, Criatividade e Tecnologia), visando promover o Pensamento Computacional (PC) no Brasil por meio da criação e disponibilização de recursos pedagógicos.

## Pré-requisitos
Antes de instalar as dependências, você precisa ter as seguintes ferramentas instaladas em seu ambiente:

- [Node.js](https://nodejs.org/) (inclui npm)
- [Composer](https://getcomposer.org/)

## Instalação

Siga as etapas abaixo para instalar as dependências necessárias do projeto.

### 1. Clonar o repositório e mudar branch

Primeiro, clone o repositório do SiPPeC para sua máquina local:

git clone https://github.com/sxaoliveira13/front-SiPPEc.git

Em seguida, acesse a pasta do projeto e altere para a branch backend

git checkout backend

### 2. Instale as dependências

Você vai precisar ter o node, o npm e o composer instalados na sua máquina

Após a instalação, execute os seguintes comandos via terminal do seu editor na raiz do projeto (ex: C:\xampp\htdocs\sippec):

npm install

composer install

### 3. Adicione um arquivo .env na raiz da pasta api

O arquivo .env contém as credenciais de acesso ao banco de dados do liag. Ele não está versionado no github por questões de segurança. Fale comigo (19) 99868-5541

### 4. Crie o banco de dados localmente

O arquivo sippec.sql contém a estrutura incial das tabelas do banco de dados. Usei o phpmyadmin para criar o banco.

### 5. Abra o site localhost utilizando um servidor Apache (mesmo tipo do server em produção)

Utilizei o XAMPP como servidor local

### 6. Compile os arquivos SCSS em CSS

Não faça nenhuma alteração direta no arquivo main.css

No seu terminal, execute npm run watch sass (esse comando pode ser encontrado no package.json)

Feito isso, qualquer alteração em arquivos sass vai se refletir no main.css
