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
document.addEventListener("DOMContentLoaded", function () {
    function setupSlider(sliderClass) {
        const track = document.getElementById(`${sliderClass}-track`);
        const prevBtn = document.querySelector(`.prev-btn[data-slider="${sliderClass}"]`);
        const nextBtn = document.querySelector(`.next-btn[data-slider="${sliderClass}"]`);
        const books = document.querySelectorAll(`.${sliderClass}-books`);

        if (!track || !prevBtn || !nextBtn || books.length === 0) return;

        let currentIndex = 0;
        const bookWidth = books[0].offsetWidth + 10;
        const visibleBooks = Math.floor(track.parentElement.offsetWidth / bookWidth);
        const maxIndex = books.length - visibleBooks;

        function updateSlider() {
            track.style.transform = `translateX(-${currentIndex * bookWidth}px)`;
        }

        nextBtn.addEventListener("click", function () {
            if (currentIndex < maxIndex) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateSlider();
        });

        prevBtn.addEventListener("click", function () {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = maxIndex;
            }
            updateSlider();
        });
    }

    // Gọi hàm setup cho từng slider
    const sliders = ["vanhoc", "tamlykynangsong", "kinhte", "thieunhi", "tamlygioitinh"];
    sliders.forEach(setupSlider);
});

// ======search box=====
function searchProduct() {
    let keyword = document.getElementById("searchInput").value.trim();
    if (keyword === "") {
        alert("Vui lòng nhập từ khóa tìm kiếm!");
        return;
    }
    window.location.href = `index.php?search=${encodeURIComponent(keyword)}`;
}

// ====danh muc san pham====
function toggleMenu(menuId) {
    var submenu = document.getElementById(menuId);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}


// ====voucher===
document.addEventListener("DOMContentLoaded", function() {
    loadSavedVouchers();
});

// Lưu mã giảm giá
function saveVoucher(code) {
    let savedVouchers = JSON.parse(localStorage.getItem("savedVouchers")) || [];
    if (!savedVouchers.includes(code)) {
        savedVouchers.push(code);
        localStorage.setItem("savedVouchers", JSON.stringify(savedVouchers));
    }
    loadSavedVouchers();
}

// Hiển thị mã đã lưu
function loadSavedVouchers() {
    let savedVouchers = JSON.parse(localStorage.getItem("savedVouchers")) || [];
    let container = document.getElementById("saved-vouchers");
    container.innerHTML = "";
    savedVouchers.forEach(code => {
        let btn = document.createElement("div");
        btn.classList.add("saved-voucher");
        btn.innerText = code;
        btn.onclick = function() {
            document.getElementById("voucher-code").value = code;
        };
        container.appendChild(btn);
    });
}

// Kiểm tra mã giảm giá hợp lệ
function applyVoucher() {
    let voucher = document.getElementById("voucher-code").value;
    let message = document.getElementById("voucher-message");

    let validVouchers = {
        "SALE10": 10,
        "FREESHIP": 0,
        "DISCOUNT50": 50
    };

    if (validVouchers[voucher]) {
        message.innerHTML = `🎉 Mã hợp lệ! Giảm ${validVouchers[voucher]}% đơn hàng!`;
        message.style.color = "green";
    } else {
        message.innerHTML = "❌ Mã không hợp lệ. Vui lòng thử lại.";
        message.style.color = "red";
    }
}
let showVoucher = true; // Biến kiểm tra trạng thái hiển thị

function toggleVoucherSection() {
    let voucherSection = document.getElementById("voucher-input-section");
    let promoSection = document.getElementById("promotion-section");

    if (showVoucher) {
        voucherSection.style.display = "none";
        promoSection.style.display = "block";
    } else {
        voucherSection.style.display = "block";
        promoSection.style.display = "none";
    }

    showVoucher = !showVoucher;
}

// Tự động chuyển đổi nội dung mỗi 3 giây
setInterval(toggleVoucherSection, 7000);

// ====================
// Tạo hiệu ứng hoa rơi bằng emoji
// Danh sách phần tử: hoa, lá, nhụy
// Khai báo emoji với class riêng
const petalTypes = [
    { emoji: '🌸', class: 'flower' },    // Hoa đào
    { emoji: '🍃', class: 'leaf' },      // Lá
    { emoji: '●', class: 'center' }      // Nhụy hoa
];

// Hàm tạo một icon rơi
function createPetal(type) {
    const petal = document.createElement('div');
    petal.className = 'blossom ' + type.class;
    petal.innerText = type.emoji;

    petal.style.left = Math.random() * window.innerWidth + 'px';
    petal.style.fontSize = Math.random() * 12 + 16 + 'px';
    petal.style.animationDuration = Math.random() * 4 + 4 + 's';
    petal.style.opacity = Math.random();

    document.body.appendChild(petal);
    setTimeout(() => petal.remove(), 10000);
}

// Chạy rơi hoa
if (window.innerWidth > 768) {
    setInterval(() => {
        // Rơi 10 icon mỗi đợt
        for (let i = 0; i < 10; i++) {
            if (i < 5) {
                // 5 icon đầu là hoa
                createPetal(petalTypes[0]);
            } else {
                // 5 icon còn lại: ngẫu nhiên giữa lá và nhụy
                const random = Math.floor(Math.random() * 2) + 1; // 1 hoặc 2
                createPetal(petalTypes[random]);
            }
        }
    }, 1000); // Cứ mỗi 1 giây rơi 10 item
}

// CSS chèn từ JS
const style = document.createElement('style');
style.innerHTML = `
  .blossom {
    position: fixed;
    top: -40px;
    pointer-events: none;
    z-index: 9999;
    animation: fall linear forwards;
  }

  .blossom.flower {
    color: #ff69b4;
  }

  .blossom.leaf {
    color: #2ecc71;
  }

  .blossom.center {
    color: gold;
  }

  @keyframes fall {
    to {
      transform: translateY(100vh) rotate(360deg);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);