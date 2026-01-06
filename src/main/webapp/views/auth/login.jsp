<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Web Absensi Giki</title>
        <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>

    <body
        style="justify-content: center; align-items: center; background: linear-gradient(135deg, #4F46E5 0%, #06b6d4 100%);">

        <div class="card" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text);">Selamat Datang</h1>
                <p style="color: var(--text-light);">Silakan login untuk melanjutkan</p>
            </div>

            <% String error=(String) request.getAttribute("error"); %>
                <% if (error !=null) { %>
                    <div class="alert alert-danger">
                        <%= error %>
                    </div>
                    <% } %>

                        <form action="${pageContext.request.contextPath}/login" method="POST">
                            <div class="form-group">
                                <label class="form-label">Username / NIP / NIS</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                                    required autofocus>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-full">Masuk</button>
                        </form>
        </div>

    </body>

    </html>