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

@WebServlet("/tatausaha/data-guru")
public class TUGuruServlet extends HttpServlet {
    private UserDAO userDAO = new UserDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        if (!isAuthorized(req)) {
            resp.sendRedirect("../login");
            return;
        }

        List<User> listGuru = userDAO.findAllByRole("guru");
        req.setAttribute("listGuru", listGuru);
        req.getRequestDispatcher("/views/tatausaha/data-guru.jsp").forward(req, resp);
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
            resp.sendRedirect("data-guru?msg=deleted");
            return;
        }

        // Add Guru
        String name = req.getParameter("name");
        String username = req.getParameter("username");
        String password = req.getParameter("password");
        String email = req.getParameter("email");
        String nip = req.getParameter("nip");
        String mapel = req.getParameter("mapel");

        User u = new User();
        u.setName(name);
        u.setUsername(username);
        u.setPassword(BCrypt.hashpw(password, BCrypt.gensalt()));
        u.setRole("guru");
        u.setEmail(email);
        u.setNipNis(nip);
        u.setMapelDiampu(mapel);

        userDAO.createUser(u);

        resp.sendRedirect("data-guru?msg=created");
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
