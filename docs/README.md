# 📁 Estrutura do Projeto

```text
todo-list-system-web/
│
├── config/                   
│   Contém arquivos de configuração geral do sistema. 
│   Exemplo: conexão com banco de dados, variáveis globais, etc.
│
├── docs/                     
│   Espaço reservado para documentação, anotações e diagramas.
│
├── public/                   
│   Pasta pública do sistema (acessada via navegador).
│   Aqui ficam os arquivos que o servidor expõe.
│   ├── assets/               → CSS, JavaScript e imagens.
│   ├── pages/                → Páginas PHP que compõem a interface (login, home, etc).
│
├── src/                      
│   ├── actions/              → Processamento de ações (CRUD).
│   └── includes/             → Funções auxiliares e autenticação;
 ``` 

## 🧭 Organização Geral

O projeto segue o padrão de **separação entre front-end e back-end**, onde o front fica em `public/` e o back nas pastas `src/` e `config/`.

- As ações (como adicionar, atualizar ou deletar tarefas) são processadas em arquivos dentro de `src/actions/`.
- A autenticação e verificação de sessão são feitas por scripts dentro de `src/includes/`.
- O `config/db.php` é responsável por conectar o sistema ao banco de dados via **PDO**.

> Obs: Os arquivos `.keep` são descartáveis. Eles estão presentes apenas para permitir que pastas vazias sejam incluídas no repositório.

## ⚙️ Boas Práticas para Contribuir

- **Não editar diretamente arquivos dentro de `public/`**, a menos que seja relacionado à interface (HTML/CSS/JS).  
- Toda alteração em lógica deve estar dentro da pasta `src/`.  
- Evite duplicar funções — verifique se já existe algo parecido em `includes/`.  
- Sempre **testar localmente** antes de enviar alterações.  

---
