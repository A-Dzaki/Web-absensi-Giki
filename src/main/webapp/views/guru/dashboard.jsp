<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <title>Guru Dashboard</title>
            <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
        </head>

        <body>
            <nav style="background: white; padding: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);">
                <div class="container flex justify-between items-center">
                    <h2 style="color: var(--primary); font-weight: 700;">Guru Dashboard</h2>
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
                    <h3>Jadwal Mengajar Anda</h3>
                    <p style="color: var(--text-light); margin-bottom: 1rem;">Berikut adalah daftar jadwal kelas yang
                        Anda ampu.</p>

                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; background: #f1f5f9;">
                                <th style="padding: 0.75rem;">Hari</th>
                                <th style="padding: 0.75rem;">Jam</th>
                                <th style="padding: 0.75rem;">Kelas</th>
                                <th style="padding: 0.75rem;">Mapel</th>
                                <th style="padding: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <c:forEach var="j" items="${jadwals}">
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 0.75rem;">${j.hari}</td>
                                    <td style="padding: 0.75rem;">${j.jamMulai} - ${j.jamSelesai}</td>
                                    <td style="padding: 0.75rem;">${j.namaKelas}</td>
                                    <td style="padding: 0.75rem;">${j.mapel}</td>
                                    <td style="padding: 0.75rem;">
                                        <button class="btn btn-primary"
                                            style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Absen Siswa</button>
                                    </td>
                                </tr>
                            </c:forEach>
                            <c:if test="${empty jadwals}">
                                <tr>
                                    <td colspan="5"
                                        style="padding: 1rem; text-align: center; color: var(--text-light);">Tidak ada
                                        jadwal ditemukan.</td>
                                </tr>
                            </c:if>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>

        </html>