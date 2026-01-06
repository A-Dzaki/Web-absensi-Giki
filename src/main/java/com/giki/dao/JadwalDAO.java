package com.giki.dao;

import com.giki.config.DatabaseConnection;
import com.giki.models.Jadwal;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class JadwalDAO {

    public List<Jadwal> getJadwalByGuru(int guruId) {
        List<Jadwal> list = new ArrayList<>();
        String sql = "SELECT j.*, k.nama_kelas FROM jadwals j JOIN kelas k ON j.kelas_id = k.id WHERE j.guru_id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, guruId);
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    Jadwal j = new Jadwal();
                    j.setId(rs.getInt("id"));
                    j.setKelasId(rs.getInt("kelas_id"));
                    j.setGuruId(rs.getInt("guru_id"));
                    j.setMapel(rs.getString("mapel"));
                    j.setHari(rs.getString("hari"));
                    j.setJamMulai(rs.getTime("jam_mulai"));
                    j.setJamSelesai(rs.getTime("jam_selesai"));
                    j.setNamaKelas(rs.getString("nama_kelas"));
                    list.add(j);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return list;
    }

    public Jadwal findActiveJadwal(String namaKelas, String hari, Time jam) {
        // Need to join with Kelas because User has 'nama_kelas' string, but Jadwal has
        // 'kelas_id'
        String sql = "SELECT j.*, k.nama_kelas FROM jadwals j " +
                "JOIN kelas k ON j.kelas_id = k.id " +
                "WHERE k.nama_kelas = ? AND j.hari = ? AND ? BETWEEN j.jam_mulai AND j.jam_selesai";

        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, namaKelas);
            pstmt.setString(2, hari);
            pstmt.setTime(3, jam);

            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    Jadwal j = new Jadwal();
                    j.setId(rs.getInt("id"));
                    j.setKelasId(rs.getInt("kelas_id"));
                    j.setGuruId(rs.getInt("guru_id"));
                    j.setMapel(rs.getString("mapel")); // or mata_pelajaran
                    // ... map other fields
                    return j;
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    // Helper to get students by class (using UserDAO logic but specific list)
    // Actually should be in UserDAO but putting here for context or separate
    // StudentDAO
}
