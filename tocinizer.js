window.onload = function() {
    var sections = document.querySelectorAll('.nflrc_divi_post_content > h2');
    var toc = document.querySelector('#toc');
    var menuitem = document.createElement('li');
    var anchor = document.createElement('a');   
    for (let i = 0; i < sections.length; i++) {
        var s = sections[i];
        menuitem = document.createElement('li');
        anchor = document.createElement('a');
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
            document.querySelectorAll('.active').forEach(function(element) {
                element.classList.remove('active');
            });
            
            // section.parentElement.style.height = '200%';
            section.scrollIntoView(); 
            section.classList.add('active');
            e.target.classList.add('active');
        });        
    }
    document.querySelector('#menutop').addEventListener('click', function(e) {
        document.querySelectorAll('.active').forEach(function(element) {
            element.classList.remove('active');
        });  
    });

};


