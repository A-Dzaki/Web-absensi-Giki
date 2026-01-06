<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>TU Dashboard</title>
        <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
    </head>

    <body>
        <nav style="background: white; padding: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);">
            <div class="container flex justify-between items-center">
                <h2 style="color: var(--primary); font-weight: 700;">Tata Usaha</h2>
                <div class="flex items-center gap-4" style="gap: 1rem;">
                    <span>Halo, ${sessionScope.user.name}</span>
                    <form action="${pageContext.request.contextPath}/logout" method="POST" style="margin:0;">
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <div class="card mb-4">
                <h3>Panel Admin</h3>
                <p style="margin-bottom: 2rem;">Selamat datang di panel administrasi.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="card" style="background: #f8fafc; text-align: center; border: 1px solid #e2e8f0;">
                        <h4 style="color: var(--text-light);">Kelola Guru</h4>
                        <button class="btn btn-primary mt-4" style="width: 100%;">Lihat Data</button>
                    </div>
                    <div class="card" style="background: #f8fafc; text-align: center; border: 1px solid #e2e8f0;">
                        <h4 style="color: var(--text-light);">Kelola Siswa</h4>
                        <button class="btn btn-primary mt-4" style="width: 100%;">Lihat Data</button>
                    </div>
                    <div class="card" style="background: #f8fafc; text-align: center; border: 1px solid #e2e8f0;">
                        <h4 style="color: var(--text-light);">Rekap Absensi</h4>
                        <button class="btn btn-primary mt-4" style="width: 100%;">Export Data</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>