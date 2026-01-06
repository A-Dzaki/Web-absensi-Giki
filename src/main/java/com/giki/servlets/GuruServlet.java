package com.giki.servlets;

import com.giki.dao.JadwalDAO;
import com.giki.models.Jadwal;
import com.giki.models.User;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.util.List;

@WebServlet("/guru/dashboard")
public class GuruServlet extends HttpServlet {
    private JadwalDAO jadwalDAO = new JadwalDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session == null || session.getAttribute("user") == null) {
            resp.sendRedirect(req.getContextPath() + "/login");
            return;
        }

        User user = (User) session.getAttribute("user");
        if (!"guru".equals(user.getRole())) {
            resp.sendError(HttpServletResponse.SC_FORBIDDEN);
            return;
        }

        List<Jadwal> jadwals = jadwalDAO.getJadwalByGuru(user.getId());
        req.setAttribute("jadwals", jadwals);

        req.getRequestDispatcher("/views/guru/dashboard.jsp").forward(req, resp);
    }
}
