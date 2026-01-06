package com.giki.models;

public class Kelas {
    private int id;
    private String namaKelas;

    public Kelas() {}
    public Kelas(int id, String namaKelas) {
        this.id = id;
        this.namaKelas = namaKelas;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getNamaKelas() { return namaKelas; }
    public void setNamaKelas(String namaKelas) { this.namaKelas = namaKelas; }
}
