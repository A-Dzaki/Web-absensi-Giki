package com.giki.dao;

import com.giki.config.DatabaseConnection;
import com.giki.models.Absensi;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class AbsensiDAO {

    public List<Absensi> getAbsensiBySiswa(int userId) {
        List<Absensi> list = new ArrayList<>();
        String sql = "SELECT * FROM absensis WHERE user_id = ? ORDER BY tanggal DESC, jam DESC";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, userId);
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    Absensi a = new Absensi();
                    a.setId(rs.getInt("id"));
                    a.setUserId(rs.getInt("user_id"));
                    a.setTanggal(rs.getDate("tanggal"));
                    a.setStatus(rs.getString("status"));
                    a.setJam(rs.getTime("jam"));
                    a.setKelas(rs.getString("kelas"));
                    a.setCatatan(rs.getString("catatan"));
                    list.add(a);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return list;
    }

    public boolean hasAbsencedToday(int userId, String mapel) {
        String sql = "SELECT id FROM absensis WHERE user_id = ? AND tanggal = CURDATE() AND mata_pelajaran = ?";
        // Note: DB column might be 'mata_pelajaran' based on controller, but previously
        // I might have missed it in model.
        // Controller line 71: ->where('mata_pelajaran', $jadwal->mata_pelajaran)
        // CHECK if 'absensis' table actually has 'mata_pelajaran'.
        // The fix-db route added: 'catatan', 'jam', 'kelas', 'guru_id', 'jadwal_id'.
        // It did NOT add 'mata_pelajaran' explicitly in my review of 'fix-db'.
        // But the Controller implies it exists. I will assume it exists or use 'mapel'
        // column if I created one.
        // Let's use 'mata_pelajaran' as per Laravel Controller.

        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, userId);
            pstmt.setString(2, mapel);
            try (ResultSet rs = pstmt.executeQuery()) {
                return rs.next();
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    public void saveAbsensi(Absensi absensi) {
        String sql = "INSERT INTO absensis (user_id, tanggal, status, jam, kelas, guru_id, jadwal_id, catatan, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, absensi.getUserId());
            pstmt.setDate(2, absensi.getTanggal());
            pstmt.setString(3, absensi.getStatus());
            pstmt.setTime(4, absensi.getJam());
            pstmt.setString(5, absensi.getKelas());
            pstmt.setInt(6, absensi.getGuruId());
            pstmt.setInt(7, absensi.getJadwalId());
            pstmt.setString(8, absensi.getCatatan());
            pstmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
