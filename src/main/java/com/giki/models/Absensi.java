package com.giki.models;

import java.sql.Timestamp;
import java.sql.Date;
import java.sql.Time;

public class Absensi {
    private int id;
    private int userId;
    private Date tanggal;
    private String status; // Hadir, Izin, Sakit, Alpha
    private Timestamp createdAt;
    private String catatan;
    private Time jam;
    private String kelas;
    private int guruId;
    private int jadwalId;

    public Absensi() {}

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getUserId() { return userId; }
    public void setUserId(int userId) { this.userId = userId; }

    public Date getTanggal() { return tanggal; }
    public void setTanggal(Date tanggal) { this.tanggal = tanggal; }

    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }

    public Timestamp getCreatedAt() { return createdAt; }
    public void setCreatedAt(Timestamp createdAt) { this.createdAt = createdAt; }

    public String getCatatan() { return catatan; }
    public void setCatatan(String catatan) { this.catatan = catatan; }

    public Time getJam() { return jam; }
    public void setJam(Time jam) { this.jam = jam; }

    public String getKelas() { return kelas; }
    public void setKelas(String kelas) { this.kelas = kelas; }

    public int getGuruId() { return guruId; }
    public void setGuruId(int guruId) { this.guruId = guruId; }

    public int getJadwalId() { return jadwalId; }
    public void setJadwalId(int jadwalId) { this.jadwalId = jadwalId; }
}
