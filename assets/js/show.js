const show = document.getElementById('buttons');
const btn = document.getElementsByClassName('btn')

show.addEventListener('mouseover', function mouseOver(){
    btn.style.display = 'inline';
});

show.addEventListener('mouseleave', function mouseLeave(){
    btn.style.display = "none";
});