<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <title>Kelola Siswa</title>
            <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
        </head>

        <body>
            <div class="container mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h2>Data Siswa</h2>
                    <a href="dashboard" class="btn btn-primary" style="background: var(--secondary);">Kembali ke
                        Dashboard</a>
                </div>

                <div class="card mb-4">
                    <h3>Tambah Siswa Baru</h3>
                    <form action="data-siswa" method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="flex gap-4" style="gap: 1rem;">
                            <div class="form-group w-full">
                                <label>NIS</label>
                                <input type="text" name="nis" class="form-control" required>
                            </div>
                            <div class="form-group w-full">
                                <label>Kelas (Contoh: 9A)</label>
                                <input type="text" name="kelas" class="form-control" required>
                            </div>
                        </div>
                        <div class="flex gap-4" style="gap: 1rem;">
                            <div class="form-group w-full">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group w-full">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Siswa</button>
                    </form>
                </div>

                <div class="card">
                    <h3>Daftar Siswa</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="text-align: left; background: #f1f5f9;">
                                <th style="padding: 0.75rem;">Nama</th>
                                <th style="padding: 0.75rem;">NIS</th>
                                <th style="padding: 0.75rem;">Kelas</th>
                                <th style="padding: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <c:forEach var="s" items="${listSiswa}">
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 0.75rem;">${s.name}</td>
                                    <td style="padding: 0.75rem;">${s.nipNis}</td>
                                    <td style="padding: 0.75rem;">${s.kelas}</td>
                                    <td style="padding: 0.75rem;">
                                        <form action="data-siswa" method="POST"
                                            onsubmit="return confirm('Hapus siswa ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="${s.id}">
                                            <button type="submit" class="btn btn-danger"
                                                style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            </c:forEach>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>

        </html>