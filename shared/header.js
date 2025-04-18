var headerHTML = `
  <header>
    <a href="../customer/homepage.html">
      <image
        class="logo"
        src="../images/logo.png"
        alt="Logo" />
    </a>
    <div class="header-buttons">
      <button class="login" onclick="openModal('loginModal')">الدخول</button>
      <button class="sign-up" onclick="openModal('signupModal')">
        التسجيل
      </button>
    </div>
  </header>
`;

// check user login status and adjust header based on it
let urlParams = new URLSearchParams(window.location.search);
const email = urlParams.get("email");
const role = urlParams.get("role");

if (email != undefined && role == "admin") {
  headerHTML = `
   <header>
      <a href="../admin/landing_page.php?email=${email}&role=admin">
        <image
          class="logo"
          src="../images/logo.png"
          alt="Logo" />
      </a>
    </header>
  `;
} else if (email != undefined && role == "customer") {
  headerHTML = `
   <header>
      <a href="../customer/homepage.html">
        <image
          class="logo"
          src="../images/logo.png"
          alt="Logo" />
      </a>
      <nav>
      <a href="../customer/homepage.html?email=${email}&role=customer">الرئيسية</a>
      <a href="../customer/cart.html?email=${email}&role=customer">السلة</a>
      <a href="../customer/prevOrders.html?email=${email}&role=customer">الطلبات السابقة</a>
    </nav>
    </header>
  `;
}

let footerHTML = `
    <footer>
      <p class="about-us">
        من نحن؟ <br />
        دلة هو مقهى سعودي مختص بالقهوة العربية من البن حتى طريقة التحضير
      </p>
    </footer>
 `;

document.addEventListener("DOMContentLoaded", function () {
  document.body.insertAdjacentHTML("afterbegin", headerHTML);
  document.body.insertAdjacentHTML("beforeend", footerHTML);
});
