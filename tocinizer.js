window.onload = function() {
    var sections = document.querySelectorAll('div.et_pb_post_content_0_tb_body > h2');
    var toc = document.querySelector('#toc');
    for (let i = 0; i < sections.length; i++) {
        var s = sections[i];
        var menuitem = document.createElement('li');
        var anchor = document.createElement('a');
        s.id = 'section' + i;
        anchor.href = '#';
        anchor.setAttribute('data-starget', '#'+s.id);
        anchor.textContent = s.textContent;
        anchor.classList.add('tocbtn');
        menuitem.append(anchor);
        toc.append(menuitem);

        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            section = document.querySelector(e.target.getAttribute('data-starget'));
            section.parentElement.style.height = '200%';
            section.scrollIntoView({behavior: 'smooth'});
            document.querySelectorAll('.active').forEach(function(element) {
                element.classList.remove('active');
            });
            section.classList.add('active');
            e.target.classList.add('active');
        });        
    }
};


