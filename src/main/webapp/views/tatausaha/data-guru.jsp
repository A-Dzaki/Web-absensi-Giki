<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <title>Siswa Dashboard</title>
            <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
        </head>

        <body>
            <div class="container mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h2>Data Guru</h2>
                    <a href="dashboard" class="btn btn-primary" style="background: var(--secondary);">Kembali ke
                        Dashboard</a>
                </div>

                <div class="card mb-4">
                    <h3>Tambah Guru Baru</h3>
                    <form action="data-guru" method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="flex gap-4" style="gap: 1rem;">
                            <div class="form-group w-full">
                                <label>NIP</label>
                                <input type="text" name="nip" class="form-control" required>
                            </div>
                            <div class="form-group w-full">
                                <label>Mapel Diampu</label>
                                <input type="text" name="mapel" class="form-control">
                            </div>
                        </div>
                        <div class="flex gap-4" style="gap: 1rem;">
                            <div class="form-group w-full">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group w-full">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Guru</button>
                    </form>
                </div>

                <div class="card">
                    <h3>Daftar Guru</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="text-align: left; background: #f1f5f9;">
                                <th style="padding: 0.75rem;">Nama</th>
                                <th style="padding: 0.75rem;">NIP</th>
                                <th style="padding: 0.75rem;">Mapel</th>
                                <th style="padding: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <c:forEach var="g" items="${listGuru}">
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 0.75rem;">${g.name}</td>
                                    <td style="padding: 0.75rem;">${g.nipNis}</td>
                                    <td style="padding: 0.75rem;">${g.mapelDiampu}</td>
                                    <td style="padding: 0.75rem;">
                                        <form action="data-guru" method="POST"
                                            onsubmit="return confirm('Hapus guru ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="${g.id}">
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