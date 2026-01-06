package com.giki.models;

public class User {
    private int id;
    private String name;
    private String username;
    private String password; // Hash
    private String role; // guru, siswa, tatausaha
    private String kelas; // For Siswa
    private String email;
    private String noTelp;
    private String foto;
    private String mapelDiampu;
    private String kelasDiampu;
    private String tanggalLahir; // For password reset potentially or identity
    private String nipNis;

    public User() {
    }

    public User(int id, String name, String username, String role) {
        this.id = id;
        this.name = name;
        this.username = username;
        this.role = role;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getRole() {
        return role;
    }

    public void setRole(String role) {
        this.role = role;
    }

    public String getKelas() {
        return kelas;
    }

    public void setKelas(String kelas) {
        this.kelas = kelas;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getNoTelp() {
        return noTelp;
    }

    public void setNoTelp(String noTelp) {
        this.noTelp = noTelp;
    }

    public String getFoto() {
        return foto;
    }

    public void setFoto(String foto) {
        this.foto = foto;
    }

    public String getMapelDiampu() {
        return mapelDiampu;
    }

    public void setMapelDiampu(String mapelDiampu) {
        this.mapelDiampu = mapelDiampu;
    }

    public String getKelasDiampu() {
        return kelasDiampu;
    }

    public void setKelasDiampu(String kelasDiampu) {
        this.kelasDiampu = kelasDiampu;
    }

    public String getTanggalLahir() {
        return tanggalLahir;
    }

    public void setTanggalLahir(String tanggalLahir) {
        this.tanggalLahir = tanggalLahir;
    }

    public String getNipNis() {
        return nipNis;
    }

    public void setNipNis(String nipNis) {
        this.nipNis = nipNis;
    }
}
