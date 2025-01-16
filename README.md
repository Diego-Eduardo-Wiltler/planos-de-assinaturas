# Sistema de Planos da Claro

## Requisitos

* PHP 8.2 ou superior
* MySQL 8 ou superior
* Composer

## Como rodar o projeto baixado

Duplicar o arquivo ".env.example" e renomear para ".env".<br>
Alterar no arquivo .env as credenciais do banco de dados<br>

Instalar as dependências do PHP
```
composer install
```

Gerar a chave no arquivo .env
```
php artisan key:generate
```

Executar as migration
```
php artisan migrate
```

Executar as seed
```
php artisan db:seed
```

Iniciar o projeto criado com Laravel
```
php artisan serve
```

Para acessar a API, é recomendado utilizar o Insomnia para simular requisições à API.
```
- http://127.0.0.1:8000/api/planos
- http://127.0.0.1:8000/api/produtos

```

Link para requisições do Insomnia: [Acesse o arquivo no Google Drive](https://drive.google.com/file/d/1rYcFH5UFg_UmCImRvaO9wpKHP5h-Nj0s/view?usp=sharing)

## Decisões técnicas tomadas durante o desenvolvimento

* Insomnia para gerenciar requisições: Utilizei o Insomnia para organizar e testar as requisições, facilitando a visualização das rotas e parâmetros.

* Form Request para validação: Optei por usar Form Requests para validar os dados, o que ajuda a manter as controllers mais limpas e centraliza a lógica de validação. Apesar de não ter finalizado todas as validações, priorizei as operações de store e update.

* Controllers com recursos (Resource): Escolhi o padrão Resource para as controllers por garantir uma estrutura organizada e consistente para as rotas e métodos. Isso também deixa o código mais limpo e fácil de escalar.

* Lógicas na camada Service: Todas as lógicas foram movidas para as Services, como a associação de produtos aos planos, que está na PlanoService. Essa abordagem ajuda a isolar responsabilidades e facilita manutenções futuras.

* Muitos-para-muitos (Many-to-Many): Por ser uma relação many-to-many, a associação de produtos aos planos foi implementada diretamente no PlanoService. Isso faz sentido, pois os produtos são parte integral dos planos.

* Histórico de ações: Para registrar associações e desassociações, criei uma tabela intermediária de logs. Diferente da tabela PlanoProduto, esta tem mais campos, justificando a criação de uma model e um resource próprios para logs.

### Expansão do projeto

* Combos: Criação de pacotes que agrupam produtos e planos, permitindo ofertas combinadas.

* Contratos de Plano: Gestão de contratos associados a planos, incluindo detalhes como duração, termos e condições.

* Histórico Detalhado: Endpoints específicos para consultar o histórico de planos ou produtos, facilitando o acompanhamento de alterações e transações.

* Validações Robustas: Implementação de regras para evitar associações duplicadas de produtos a planos, garantindo a integridade dos dados.

* Novas Funcionalidades: Desenvolvimento de filtros para planos, históricos e relatórios, aprimorando a experiência do usuário e a eficiência na busca de informações.

* Bonûs: Refinar comentários
