window.addEventListener("scroll", function() {
    var header = document.querySelector(".header");
    if (window.scrollY > 50) {
        header.classList.add("shrink");
    } else {
        header.classList.remove("shrink");
    }
});

// =====slide show=======
// Slideshow
let slideIndex = 1;

// Gọi hàm showSlides ngay khi trang tải để hiển thị slide đầu tiên
document.addEventListener("DOMContentLoaded", function () {
    showSlides(slideIndex);
});

// Hàm hiển thị slide
function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");

    // Xử lý chỉ số slide
    if (n > slides.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = slides.length; }

    // Ẩn tất cả các slide
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    // Xóa trạng thái active của tất cả các chấm
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    // Hiển thị slide hiện tại
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

// Hàm chuyển slide bằng nút điều hướng
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Hàm chuyển đến slide cụ thể bằng chấm chỉ số
function currentSlide(n) {
    showSlides(slideIndex = n);
}

// Tự động chuyển slide sau mỗi 5 giây
setInterval(() => {
    plusSlides(1);
}, 5000);


// ======hien thi so luong san pham trong gio hang=====
function updateCartCount() {
    fetch("cart_count.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("cart-count").innerText = data;
        });
}

// Gọi hàm cập nhật giỏ hàng mỗi 5 giây
setInterval(updateCartCount, 5000);

//======danh muc san pham========
function toggleMenu(id) {
    var submenu = document.getElementById(id);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}
// ======sach van hoc=======
document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".slider-track");
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");
    const books = document.querySelectorAll(".vanhoc-book");

    let currentIndex = 0;
    const bookWidth = books[0].offsetWidth + 10; // Kích thước mỗi sách + khoảng cách
    const maxIndex = books.length - 3; // Hiển thị 3 sách cùng lúc (có thể thay đổi)

    nextBtn.addEventListener("click", function () {
        if (currentIndex < maxIndex) {
            currentIndex++;
            track.style.transform = `translateX(-${currentIndex * bookWidth}px)`;
        }
    });

    prevBtn.addEventListener("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
            track.style.transform = `translateX(-${currentIndex * bookWidth}px)`;
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    function setupSlider(containerSelector, trackSelector, prevSelector, nextSelector, bookSelector) {
        const container = document.querySelector(containerSelector);
        const track = document.querySelector(trackSelector);
        const prevBtn = document.querySelector(prevSelector);
        const nextBtn = document.querySelector(nextSelector);
        const books = document.querySelectorAll(bookSelector);

        if (!container || !track || !prevBtn || !nextBtn || books.length === 0) return;

        let currentIndex = 0;
        const bookWidth = books[0].offsetWidth + 10; // Kích thước mỗi sách + khoảng cách
        const visibleBooks = Math.floor(container.offsetWidth / bookWidth);
        const maxIndex = books.length - visibleBooks;

        function updateSlider() {
            track.style.transform = `translateX(-${currentIndex * bookWidth}px)`;
        }

        nextBtn.addEventListener("click", function () {
            if (currentIndex < maxIndex) {
                currentIndex++;
            } else {
                currentIndex = 0; // Quay lại đầu nếu đến cuối
            }
            updateSlider();
        });

        prevBtn.addEventListener("click", function () {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = maxIndex; // Quay về cuối nếu ở đầu
            }
            updateSlider();
        });
    }

    //Setup từng slider riêng biệt
    setupSlider(".vanhoc-slider-container", ".vanhoc-slider-container .slider-track", ".vanhoc-slider-container .prev-btn", ".vanhoc-slider-container .next-btn", ".vanhoc-book");
    setupSlider(".sach-tam-ly-ky-nang-song-slider-container", ".sach-tam-ly-ky-nang-song-slider-container .slider-track", ".sach-tam-ly-ky-nang-song-slider-container .prev-btn", ".sach-tam-ly-ky-nang-song-slider-container .next-btn", ".tamlykynangsong-book");
});
