package com.giki.dao;

import com.giki.config.DatabaseConnection;
import com.giki.models.User;
import org.mindrot.jbcrypt.BCrypt;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class UserDAO {

    public User authenticate(String username, String password) {
        String sql = "SELECT * FROM users WHERE username = ? OR nip_nis = ?"; // Allow login by Username or NIP/NIS
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setString(1, username);
            pstmt.setString(2, username);

            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    String storedHash = rs.getString("password");
                    if (BCrypt.checkpw(password, storedHash)) {
                        User user = new User();
                        user.setId(rs.getInt("id"));
                        user.setName(rs.getString("name"));
                        user.setUsername(rs.getString("username"));
                        user.setRole(rs.getString("role"));
                        user.setNipNis(rs.getString("nip_nis"));
                        user.setMapelDiampu(rs.getString("mapel_diampu"));
                        user.setKelasDiampu(rs.getString("kelas_diampu"));
                        return user;
                    }
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    public User findByUid(String uid) {
        String sql = "SELECT * FROM users WHERE uid_rfid = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, uid);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    return mapUser(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    public java.util.List<User> findAllByRole(String role) {
        java.util.List<User> list = new java.util.ArrayList<>();
        String sql = "SELECT * FROM users WHERE role = ? ORDER BY name";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, role);
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next())
                    list.add(mapUser(rs));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return list;
    }

    private User mapUser(ResultSet rs) throws SQLException {
        User user = new User();
        user.setId(rs.getInt("id"));
        user.setName(rs.getString("name"));
        user.setUsername(rs.getString("username"));
        user.setRole(rs.getString("role"));
        user.setNipNis(rs.getString("nip_nis") != null ? rs.getString("nip_nis") : rs.getString("nis")); // Handle both
        user.setKelas(rs.getString("kelas")); // Map kelas
        user.setMapelDiampu(rs.getString("mapel_diampu"));
        user.setKelasDiampu(rs.getString("kelas_diampu"));
        // Add other fields as needed
        return user;
    }

    public void updateUser(User user) {
        String sql = "UPDATE users SET name=?, email=?, no_telp=?, foto=?, password=? WHERE id=?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setString(1, user.getName());
            pstmt.setString(2, user.getEmail());
            pstmt.setString(3, user.getNoTelp());
            pstmt.setString(4, user.getFoto());
            pstmt.setString(5, user.getPassword());
            pstmt.setInt(6, user.getId());
            pstmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void createUser(User user) {
        String sql = "INSERT INTO users (name, username, password, role, email, no_telp, foto, nis, nip_nis, mapel_diampu, kelas_diampu, kelas, uid_rfid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {

            pstmt.setString(1, user.getName());
            pstmt.setString(2, user.getUsername());
            pstmt.setString(3, user.getPassword());
            pstmt.setString(4, user.getRole());
            pstmt.setString(5, user.getEmail());
            pstmt.setString(6, user.getNoTelp());
            pstmt.setString(7, user.getFoto());
            pstmt.setString(8, user.getNipNis());
            pstmt.setString(9, user.getNipNis());
            pstmt.setString(10, user.getMapelDiampu());
            pstmt.setString(11, user.getKelasDiampu());
            pstmt.setString(12, user.getKelas());
            pstmt.setString(13, null);

            pstmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void deleteUser(int id) {
        String sql = "DELETE FROM users WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, id);
            pstmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
