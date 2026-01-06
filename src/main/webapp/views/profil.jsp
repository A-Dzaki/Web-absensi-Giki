<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Profil Saya</title>
        <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="container mt-4">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <div class="flex justify-between items-center mb-4">
                    <h2>Profil Saya</h2>
                    <a href="${pageContext.request.contextPath}/" class="btn btn-primary"
                        style="background: var(--secondary);">Kembali</a>
                </div>

                <% if (request.getParameter("success") !=null) { %>
                    <div class="alert" style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">
                        Profil berhasil diperbarui!
                    </div>
                    <% } %>

                        <form action="${pageContext.request.contextPath}/profil" method="POST"
                            enctype="multipart/form-data">
                            <div class="text-center mb-4">
                                <img src="${sessionScope.user.foto != null ? pageContext.request.contextPath + '/' + sessionScope.user.foto : 'https://ui-avatars.com/api/?name=' + sessionScope.user.name}"
                                    alt="Foto Profil"
                                    style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #e2e8f0;">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="${sessionScope.user.name}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="${sessionScope.user.email}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="no_telp" class="form-control"
                                    value="${sessionScope.user.noTelp}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Ganti Password (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Ganti Foto</label>
                                <input type="file" name="foto" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary w-full">Simpan Perubahan</button>
                        </form>
            </div>
        </div>
    </body>

    </html>