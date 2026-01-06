package com.giki.servlets;

import com.giki.dao.AbsensiDAO;
import com.giki.dao.JadwalDAO;
import com.giki.dao.UserDAO;
import com.giki.models.Absensi;
import com.giki.models.Jadwal;
import com.giki.models.User;
import com.google.gson.Gson;
import com.google.gson.JsonObject;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.sql.Time;
import java.time.LocalTime;
import java.time.LocalDate;
import java.time.format.TextStyle;
import java.util.Locale;

@WebServlet("/input-rfid")
public class InputRfidServlet extends HttpServlet {
    private UserDAO userDAO = new UserDAO();
    private JadwalDAO jadwalDAO = new JadwalDAO();
    private AbsensiDAO absensiDAO = new AbsensiDAO();
    private Gson gson = new Gson();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        resp.setContentType("application/json");
        resp.setCharacterEncoding("UTF-8");

        String uid = req.getParameter("uid");
        if (uid == null || uid.isEmpty()) {
            sendJson(resp, 400, "UID Required", null);
            return;
        }

        User user = userDAO.findByUid(uid);
        if (user == null) {
            sendJson(resp, 404, "Kartu Tidak Dikenal", null);
            return;
        }

        // Determine current Day (Indonesian)
        String hariIni = LocalDate.now().getDayOfWeek().getDisplayName(TextStyle.FULL, new Locale("id", "ID"));
        if (!hariIni.matches("Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu")) {
            hariIni = mapDayToIndonesian(LocalDate.now().getDayOfWeek().toString());
        }

        Time jamSekarang = Time.valueOf(LocalTime.now());

        if (user.getKelas() == null || user.getKelas().isEmpty()) {
            sendJson(resp, 404, "Kelas Siswa Tidak Valid", null);
            return;
        }

        // Find Active Schedule
        Jadwal jadwal = jadwalDAO.findActiveJadwal(user.getKelas(), hariIni, jamSekarang);

        if (jadwal == null) {
            sendJson(resp, 404, "Tidak Ada Jadwal Saat Ini", null);
            return;
        }

        // Check Duplicate
        if (absensiDAO.hasAbsencedToday(user.getId(), jadwal.getMapel())) {
            sendJson(resp, 200, "Sudah Absen: " + user.getName(), jadwal.getMapel()); // 200 OK because it is not an
                                                                                      // error
            return;
        }

        // Create Absensi
        Absensi a = new Absensi();
        a.setUserId(user.getId());
        a.setKelas(user.getKelas());
        a.setTanggal(new java.sql.Date(System.currentTimeMillis()));
        a.setStatus("H"); // Hadir
        a.setJam(jamSekarang);
        a.setGuruId(jadwal.getGuruId());
        a.setJadwalId(jadwal.getId());
        a.setCatatan("Hadir via RFID");

        absensiDAO.saveAbsensi(a);

        sendJson(resp, 200, "Selamat Datang " + user.getName(), jadwal.getMapel());
    }

    private void sendJson(HttpServletResponse resp, int status, String msg, String mapel) throws IOException {
        resp.setStatus(status);
        JsonObject json = new JsonObject();
        json.addProperty("message", msg);
        if (mapel != null)
            json.addProperty("mapel", mapel);
        resp.getWriter().write(gson.toJson(json));
    }

    private String mapDayToIndonesian(String englishDay) {
        switch (englishDay.toUpperCase()) {
            case "MONDAY":
                return "Senin";
            case "TUESDAY":
                return "Selasa";
            case "WEDNESDAY":
                return "Rabu";
            case "THURSDAY":
                return "Kamis";
            case "FRIDAY":
                return "Jumat";
            case "SATURDAY":
                return "Sabtu";
            case "SUNDAY":
                return "Minggu";
            default:
                return englishDay;
        }
    }
}
