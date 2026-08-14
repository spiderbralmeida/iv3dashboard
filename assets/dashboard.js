(function($){
'use strict';

/* ── Clock ── */
function tick(){
    var n=new Date(),h=String(n.getHours()).padStart(2,'0'),m=String(n.getMinutes()).padStart(2,'0');
    var d=['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'][n.getDay()];
    $('#iv3clock').text(d+'  '+h+':'+m);
}
tick(); setInterval(tick,15000);

/* ── Count up ── */
function countUp(id,val){
    var el=document.getElementById(id); if(!el) return;
    var n=0,step=Math.max(1,Math.ceil(val/(600/16)));
    var t=setInterval(function(){ n+=step; if(n>=val){n=val;clearInterval(t);} el.textContent=n.toLocaleString('pt-BR'); },16);
}

function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

var statusMap={publish:'Publicado',draft:'Rascunho',pending:'Pendente',private:'Privado'};
var editIcon='<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';

/* ── Ícone vazio por tipo ── */
var emptyIcons = {
    'iv3-posts':    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
    'iv3-pages':    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
    'iv3-products': '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
};
var emptyLinks = {
    'iv3-posts':    iv3Data.adminUrl + 'post-new.php',
    'iv3-pages':    iv3Data.adminUrl + 'post-new.php?post_type=page',
    'iv3-products': iv3Data.adminUrl + 'post-new.php?post_type=product',
};
var emptyLabels = {
    'iv3-posts':    ['Nenhum post ainda', 'Criar primeiro post'],
    'iv3-pages':    ['Nenhuma página ainda', 'Criar primeira página'],
    'iv3-products': ['Nenhum produto ainda', 'Criar primeiro produto'],
};

/* ── Render list ── */
function renderList(id, items){
    var h='';
    if(!items || !items.length){
        var icon   = emptyIcons[id]  || '';
        var link   = emptyLinks[id]  || '#';
        var labels = emptyLabels[id] || ['Nenhum item','Criar'];
        h = '<div class="iv3-empty">'
          + icon
          + '<span class="iv3-empty-label">'+labels[0]+'</span>'
          + '<a class="iv3-empty-action" href="'+link+'">+ '+labels[1]+'</a>'
          + '</div>';
    } else {
        items.forEach(function(p){
            h+='<div class="iv3-row">';
            h+='<span class="iv3-dot '+esc(p.status)+'"></span>';
            h+='<span class="iv3-rtitle">'+esc(p.title)+'</span>';
            h+='<span class="iv3-rmeta">';
            h+='<span class="iv3-badge '+esc(p.status)+'">'+(statusMap[p.status]||p.status)+'</span>';
            h+='<span class="iv3-rdate">'+esc(p.date)+'</span>';
            h+='</span>';
            h+='<a class="iv3-edit-btn" href="'+esc(p.link)+'" title="Editar">'+editIcon+' Editar</a>';
            h+='</div>';
        });
    }
    $('#'+id).html(h);
}

$(function(){

    /* Stats */
    $.post(iv3Data.ajaxUrl,{action:'iv3_stats',nonce:iv3Data.nonce},function(r){
        if(!r.success) return;
        var d=r.data;
        countUp('sn-pages',d.pages);
        countUp('sn-posts',d.posts);
        countUp('sn-users',d.users);
        if(d.has_woo){
            $('.iv3-woo').show();
            countUp('sn-products',d.products);
            countUp('sn-orders',d.orders);
            $('#sn-revenue').text('R$ '+d.revenue);
        }
        $('#iv3sys').text('WP '+d.wp_version+' | PHP '+d.php_version);
    });

    /* Posts */
    $.post(iv3Data.ajaxUrl,{action:'iv3_posts',nonce:iv3Data.nonce},function(r){
        if(r.success) renderList('iv3-posts',r.data);
    });

    /* Páginas */
    $.post(iv3Data.ajaxUrl,{action:'iv3_pages',nonce:iv3Data.nonce},function(r){
        if(r.success) renderList('iv3-pages',r.data);
    });

    /* Produtos */
    if($('#iv3-products').length){
        $.post(iv3Data.ajaxUrl,{action:'iv3_products',nonce:iv3Data.nonce},function(r){
            if(r.success) renderList('iv3-products',r.data);
        });
    }
});

}(jQuery));
