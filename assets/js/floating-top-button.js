// After document loading
var scrollTop = document.getElementById("floating-top-button");
var scrollTopHideDelay = 1500;
var scrollTopHeight = 100;

let isScrolling;
let isMouseHover = false

function showScrollTop() {
    scrollTop.style.opacity = 1;
    scrollTop.style.pointerEvents = "auto";
}

function hideScrollTop() {
    scrollTop.style.opacity = 0;
    scrollTop.style.pointerEvents = "none";
}

function setScrollTopTimeout() {
    // Set a timer to hide the button after scrollTopHideDelay ms of no scrolling
    if (!isMouseHover) {
        isScrolling = setTimeout(hideScrollTop, scrollTopHideDelay);
    }
}

function clearScrollTopTimeout() {
    if (isScrolling) {
        clearTimeout(isScrolling);
    }
}

function scrollfunction(){
    // Show button only after small scroll
    if (document.body.scrollTop > scrollTopHeight || document.documentElement.scrollTop > scrollTopHeight) {
        scrollTop.style.display = "block";
    } else {
        scrollTop.style.display = "none";
    }
    clearScrollTopTimeout();
    showScrollTop();
    setScrollTopTimeout();
}

scrollTop.addEventListener("mouseleave", function (event) {
    isMouseHover = false;
    setScrollTopTimeout();
}, false);

scrollTop.addEventListener("mouseover", function (event) {
    isMouseHover = true;
    clearScrollTopTimeout();
    showScrollTop();
}, false);

window.onscroll = function(){
    scrollfunction()
};
