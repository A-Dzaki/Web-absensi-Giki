package com.giki.servlets;

import com.giki.dao.UserDAO;
import com.giki.models.User;
import org.mindrot.jbcrypt.BCrypt;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.http.Part;
import java.io.IOException;
import java.io.File;
import java.nio.file.Paths;

@WebServlet("/profil")
@MultipartConfig
public class ProfileServlet extends HttpServlet {
    private UserDAO userDAO = new UserDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session == null || session.getAttribute("user") == null) {
            resp.sendRedirect("login");
            return;
        }
        req.getRequestDispatcher("/views/profil.jsp").forward(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session == null || session.getAttribute("user") == null) {
            resp.sendRedirect("login");
            return;
        }

        User user = (User) session.getAttribute("user");

        String name = req.getParameter("name");
        String email = req.getParameter("email");
        String noTelp = req.getParameter("no_telp");
        String password = req.getParameter("password");

        if (name != null)
            user.setName(name);
        if (email != null)
            user.setEmail(email);
        if (noTelp != null)
            user.setNoTelp(noTelp);

        if (password != null && !password.isEmpty()) {
            user.setPassword(BCrypt.hashpw(password, BCrypt.gensalt()));
        }

        // Handle File Upload
        Part filePart = req.getPart("foto");
        if (filePart != null && filePart.getSize() > 0) {
            String fileName = Paths.get(filePart.getSubmittedFileName()).getFileName().toString();
            String uploadPath = getServletContext().getRealPath("") + File.separator + "uploads";
            File uploadDir = new File(uploadPath);
            if (!uploadDir.exists())
                uploadDir.mkdir();

            filePart.write(uploadPath + File.separator + fileName);
            user.setFoto("uploads/" + fileName);
        }

        userDAO.updateUser(user);
        session.setAttribute("user", user); // Update session

        resp.sendRedirect("profil?success=true");
    }
}
