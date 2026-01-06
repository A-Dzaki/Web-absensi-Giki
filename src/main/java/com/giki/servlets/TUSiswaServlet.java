package com.giki.servlets;

import com.giki.dao.UserDAO;
import com.giki.models.User;
import org.mindrot.jbcrypt.BCrypt;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.util.List;

@WebServlet("/tatausaha/data-siswa")
public class TUSiswaServlet extends HttpServlet {
    private UserDAO userDAO = new UserDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        if (!isAuthorized(req)) {
            resp.sendRedirect("../login");
            return;
        }

        List<User> listSiswa = userDAO.findAllByRole("siswa");
        req.setAttribute("listSiswa", listSiswa);
        req.getRequestDispatcher("/views/tatausaha/data-siswa.jsp").forward(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        if (!isAuthorized(req)) {
            resp.sendRedirect("../login");
            return;
        }

        String action = req.getParameter("action");

        if ("delete".equals(action)) {
            int id = Integer.parseInt(req.getParameter("id"));
            userDAO.deleteUser(id);
            resp.sendRedirect("data-siswa?msg=deleted");
            return;
        }

        // Add Siswa
        String name = req.getParameter("name");
        String username = req.getParameter("username");
        String password = req.getParameter("password");
        String nis = req.getParameter("nis");
        String kelas = req.getParameter("kelas");

        User u = new User();
        u.setName(name);
        u.setUsername(username);
        u.setPassword(BCrypt.hashpw(password, BCrypt.gensalt()));
        u.setRole("siswa");
        u.setNipNis(nis);
        u.setKelas(kelas);
        u.setEmail(username + "@student.giki"); // Auto-generate email

        userDAO.createUser(u);

        resp.sendRedirect("data-siswa?msg=created");
    }

    private boolean isAuthorized(HttpServletRequest req) {
        HttpSession s = req.getSession(false);
        if (s != null && s.getAttribute("user") != null) {
            User u = (User) s.getAttribute("user");
            return "tatausaha".equals(u.getRole());
        }
        return false;
    }
}
