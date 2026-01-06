package com.giki.models;

import java.sql.Time;

public class Jadwal {
    private int id;
    private int kelasId;
    private int guruId;
    private String mapel;
    private String hari; // Senin, Selasa...
    private Time jamMulai;
    private Time jamSelesai;
    
    // Joined data
    private String namaGuru;
    private String namaKelas;

    public Jadwal() {}

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getKelasId() { return kelasId; }
    public void setKelasId(int kelasId) { this.kelasId = kelasId; }

    public int getGuruId() { return guruId; }
    public void setGuruId(int guruId) { this.guruId = guruId; }

    public String getMapel() { return mapel; }
    public void setMapel(String mapel) { this.mapel = mapel; }

    public String getHari() { return hari; }
    public void setHari(String hari) { this.hari = hari; }

    public Time getJamMulai() { return jamMulai; }
    public void setJamMulai(Time jamMulai) { this.jamMulai = jamMulai; }

    public Time getJamSelesai() { return jamSelesai; }
    public void setJamSelesai(Time jamSelesai) { this.jamSelesai = jamSelesai; }

    public String getNamaGuru() { return namaGuru; }
    public void setNamaGuru(String namaGuru) { this.namaGuru = namaGuru; }

    public String getNamaKelas() { return namaKelas; }
    public void setNamaKelas(String namaKelas) { this.namaKelas = namaKelas; }
}
