function showCart() {
    console.log(document.querySelector(".cover"))
    document.querySelector(".cover").classList.toggle("active")
    document.querySelectorAll(".cart-items").forEach(cart => cart.classList.toggle("show"))
}

function showMenu() {
    document.querySelector(".header-bottom").classList.toggle("active")
    document.querySelector('body').classList.toggle("active")
}

function callPopup() {
    document.querySelector(".popup").classList.toggle("active")
    document.querySelector('body').classList.toggle("no-scr")
    console.log("toggle popup")
}

function closeHidden() {
    const allHiden = document.querySelectorAll(".hidden-content.inside")
    allHiden.forEach(el => el.classList.remove("active"))
    // console.log("close active")
    document.querySelector(".header-cover").classList.remove("active")
}

function openHidden(el) {
    let hidden = el.nextElementSibling
    let hiddenInside = el.querySelector(".hidden-content.inside")
    const cover = document.querySelector(".header-cover")
    closeHidden()
    cover.classList.add("active")
    cover.addEventListener('click', () => closeHidden())
    // console.log("open active")


    hiddenInside.classList.toggle("active")
    console.log(hiddenInside)
    if(hidden.style.maxHeight) {
        hidden.style.maxHeight = null
        console.log('hide')
    } else {
        hidden.style.maxHeight = hidden.scrollHeight + "px"
        console.log('show')
    }
}

