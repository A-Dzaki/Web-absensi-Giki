package com.giki.servlets;

import com.giki.dao.UserDAO;
import com.giki.models.User;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;

@WebServlet("/login")
public class AuthServlet extends HttpServlet {
    private UserDAO userDAO = new UserDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session != null && session.getAttribute("user") != null) {
            User user = (User) session.getAttribute("user");
            redirectBasedOnRole(resp, user.getRole());
            return;
        }
        req.getRequestDispatcher("/views/auth/login.jsp").forward(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        String u = req.getParameter("username");
        String p = req.getParameter("password");

        User user = userDAO.authenticate(u, p);
        if (user != null) {
            HttpSession session = req.getSession();
            session.setAttribute("user", user);
            redirectBasedOnRole(resp, user.getRole());
        } else {
            req.setAttribute("error", "Username atau Password salah!");
            req.getRequestDispatcher("/views/auth/login.jsp").forward(req, resp);
        }
    }

    private void redirectBasedOnRole(HttpServletResponse resp, String role) throws IOException {
        switch (role) {
            case "guru":
                resp.sendRedirect("guru/dashboard");
                break;
            case "siswa":
                resp.sendRedirect("siswa/dashboard");
                break;
            case "tatausaha":
                resp.sendRedirect("tatausaha/dashboard");
                break;
            default:
                resp.sendRedirect("login");
        }
    }
}
