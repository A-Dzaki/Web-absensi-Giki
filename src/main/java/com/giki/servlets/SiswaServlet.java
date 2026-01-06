package com.giki.servlets;

import com.giki.dao.AbsensiDAO;
import com.giki.models.Absensi;
import com.giki.models.User;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.util.List;

@WebServlet("/siswa/dashboard")
public class SiswaServlet extends HttpServlet {
    private AbsensiDAO absensiDAO = new AbsensiDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session == null || session.getAttribute("user") == null) {
            resp.sendRedirect(req.getContextPath() + "/login");
            return;
        }

        User user = (User) session.getAttribute("user");
        if (!"siswa".equals(user.getRole())) {
            resp.sendError(HttpServletResponse.SC_FORBIDDEN);
            return;
        }

        List<Absensi> absensis = absensiDAO.getAbsensiBySiswa(user.getId());
        req.setAttribute("absensis", absensis);

        req.getRequestDispatcher("/views/siswa/dashboard.jsp").forward(req, resp);
    }
}
