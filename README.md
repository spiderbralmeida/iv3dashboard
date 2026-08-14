# iv3 Dashboard

Plugin WordPress que substitui o painel administrativo padrão (`wp-admin/index.php`) por um dashboard moderno, responsivo e adaptado dinamicamente ao que o site tem instalado.

## Como funciona

O dashboard é 100% adaptativo — ele detecta em tempo real quais recursos o site tem ativos e ajusta a interface automaticamente, sem nenhuma configuração manual:

| Cenário | Comportamento |
|---|---|
| **WooCommerce ativo** | Mostra cards de Produtos, Pedidos (30d) e Receita (30d); grid de 4 colunas; ações rápidas de Produto/Pedidos |
| **WooCommerce ausente** | Esses cards somem por completo; grid recalculado para 3 colunas |
| **Sem posts publicados** | Cada lista (Posts, Páginas, Produtos) mostra um estado vazio ilustrado com atalho para criar o primeiro item |

A detecção é feita com `class_exists('WooCommerce')` — não depende de configuração, funciona plugando o plugin em qualquer instalação WordPress.

## Arquitetura técnica

```
iv3-dashboard.php        → bootstrap do plugin, hooks do WordPress, endpoints AJAX
templates/dashboard.php  → markup do dashboard, renderizado condicionalmente no server-side
assets/dashboard.js      → carregamento assíncrono dos dados via admin-ajax.php
assets/dashboard.css     → grid responsivo (CSS Grid), tema dark
vendor/plugin-update-checker/ → biblioteca de auto-update via GitHub Releases
```

**Principais capacidades:**

- **Remoção limpa dos widgets nativos do WP** — `remove_meta_box` em todos os widgets padrão (activity, quick press, WooCommerce status, Yoast, Elementor) via hook `wp_dashboard_setup`
- **Carregamento assíncrono via AJAX** — estatísticas, posts, páginas e produtos são buscados sob demanda (`admin-ajax.php`) em vez de bloquear o carregamento inicial da página, com skeleton loading enquanto os dados chegam
- **Segurança em duas camadas** em cada endpoint AJAX:
  - `check_ajax_referer()` — proteção contra CSRF via nonce
  - `current_user_can('manage_options')` — proteção contra acesso não autorizado por usuários de baixo privilégio
- **Escape consistente de output** — todo dado dinâmico (título de post, status, data) passa por escape antes de ser inserido no DOM (`esc()` no JS, `esc_html`/`esc_url` no PHP), prevenindo XSS
- **Auto-update nativo do WordPress** — integrado com [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker), que consulta as *Releases* deste repositório GitHub e oferece atualização direto no painel `Plugins`, sem precisar subir arquivo manualmente

## Instalação

1. Baixe o [.zip da última release](../../releases/latest)
2. No WordPress: `Plugins → Adicionar novo → Enviar plugin`
3. Ative o plugin

Depois de instalado, o próprio WordPress passa a checar automaticamente por novas versões deste repositório.

## Replicar / usar como base

Este código é livre para qualquer pessoa copiar, modificar e reaproveitar em outro projeto — veja a [LICENÇA MIT](LICENSE). Para usar como base do seu próprio dashboard:

1. Faça um fork ou baixe o código
2. Ajuste os textos, cores (`assets/dashboard.css`) e ícones (`iv3_ico()` em `templates/dashboard.php`)
3. Se for publicar seu próprio fork com auto-update, troque a URL do repositório em `iv3-dashboard.php`:
   ```php
   PucFactory::buildUpdateChecker(
       'https://github.com/SEU-USUARIO/SEU-REPO/',
       __FILE__,
       'seu-slug-de-plugin'
   );
   ```

## Requisitos

- WordPress 5.0+
- PHP 7.4+
- WooCommerce (opcional — o dashboard funciona com ou sem)

## Licença

[MIT](LICENSE) — uso livre, inclusive comercial.
