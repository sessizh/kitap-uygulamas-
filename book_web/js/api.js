const API_BASE_URL = "http://localhost/book_api/";

// Spinner Yönetimi
function showLoader() {
    const loader = document.getElementById("loader");
    if (loader) loader.style.display = "block";
}

function hideLoader() {
    const loader = document.getElementById("loader");
    if (loader) loader.style.display = "none";
}

// Yönlendirme ile Spinner
function navigateTo(url) {
    showLoader();
    setTimeout(() => {
        window.location.href = url;
    }, 500);
}

// Secure Fetch Wrapper
async function secureFetch(url, options) {
    try {
        showLoader();
        const response = await fetch(url, options);
        hideLoader();

        if (response.status === 401) {
            alert("Session expired. Please login again.");
            localStorage.removeItem("jwt_token");
            navigateTo("index.html");
            return Promise.reject("Unauthorized");
        }

        return response;
    } catch (error) {
        hideLoader();
        alert("Network Error: " + error.message);
        throw error;
    }
}

// Login İşlemi
document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");

    if (loginForm) {
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            try {
                const response = await fetch(API_BASE_URL + "auth/login.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email, password })
                });

                if (!response.ok) throw new Error("Login failed.");

                const result = await response.json();

                if (result.token) {
                    localStorage.setItem("jwt_token", result.token);
                    navigateTo("books.html");
                } else {
                    alert(result.message || "Login failed.");
                }
            } catch (error) {
                alert("Login Error: " + error.message);
            }
        });
    }

    const bookListContainer = document.getElementById("bookList");
    if (bookListContainer) {
        const token = localStorage.getItem("jwt_token");
        if (!token) {
            alert("Please login first.");
            navigateTo("index.html");
            return;
        }

        secureFetch(API_BASE_URL + "books/list.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            }
        })
        .then(response => response.json())
        .then(data => {
            bookListContainer.innerHTML = "";
            if (data.books && data.books.length > 0) {
                data.books.forEach(book => {
                    bookListContainer.innerHTML += `
                        <div class="col-md-4 mb-3">
                            <div class="card p-3 shadow-sm">
                                <h5 class="card-title">${book.title}</h5>
                                <p class="card-text">Author: ${book.author}</p>
                                <p class="card-text">Category: ${book.category}</p>
                                <button class="btn btn-warning btn-sm me-2" onclick="editBook(${book.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteBook(${book.id})">Delete</button>
                            </div>
                        </div>
                    `;
                });
            } else {
                bookListContainer.innerHTML = `<p class="text-center">No books found.</p>`;
            }
        })
        .catch(error => alert("Book List Error: " + error.message));
    }
});

// Kitap Silme
function deleteBook(id) {
    const token = localStorage.getItem("jwt_token");

    if (confirm("Are you sure you want to delete this book?")) {
        secureFetch(API_BASE_URL + "books/delete.php", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || "Book deleted.");
            window.location.reload();
        })
        .catch(error => alert("Delete Error: " + error.message));
    }
}

function logout() {
    localStorage.removeItem("jwt_token");
    navigateTo("index.html");
}

function editBook(id) {
    navigateTo(`add_edit.html?id=${id}`);
}
