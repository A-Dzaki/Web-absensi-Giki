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
            <nav style="background: white; padding: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);">
                <div class="container flex justify-between items-center">
                    <h2 style="color: var(--primary); font-weight: 700;">Siswa Dashboard</h2>
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
                    <h3>Riwayat Absensi Anda</h3>

                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="text-align: left; background: #f1f5f9;">
                                <th style="padding: 0.75rem;">Tanggal</th>
                                <th style="padding: 0.75rem;">Jam</th>
                                <th style="padding: 0.75rem;">Status</th>
                                <th style="padding: 0.75rem;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <c:forEach var="a" items="${absensis}">
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 0.75rem;">${a.tanggal}</td>
                                    <td style="padding: 0.75rem;">${a.jam}</td>
                                    <td style="padding: 0.75rem;">
                                        <span
                                            style="background: ${a.status == 'Hadir' ? '#dcfce7' : '#fee2e2'}; color: ${a.status == 'Hadir' ? '#166534' : '#991b1b'}; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.875rem;">
                                            ${a.status}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">${a.catatan}</td>
                                </tr>
                            </c:forEach>
                            <c:if test="${empty absensis}">
                                <tr>
                                    <td colspan="4" style="padding: 1rem; text-align: center;">Belum ada data absensi.
                                    </td>
                                </tr>
                            </c:if>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>

        </html>