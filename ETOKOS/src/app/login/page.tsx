"use client";

import React from "react";
import { useRouter } from "next/navigation";
import { useKosStore } from "@/lib/store";
import {
  ShieldCheck,
  UserCheck,
  Globe,
  Sparkles,
  ArrowRight,
  CheckCircle2,
  Building2,
} from "lucide-react";

export default function LoginPage() {
  const router = useRouter();
  const { setRole, setActiveTenantId } = useKosStore();

  const handleLoginAs = (role: "owner" | "penghuni" | "pengunjung") => {
    setRole(role);
    if (role === "owner") {
      router.push("/owner/dashboard");
    } else if (role === "penghuni") {
      setActiveTenantId("t-1"); // Budi Santoso
      router.push("/penghuni");
    } else {
      router.push("/");
    }
  };

  return (
    <div className="min-h-[calc(100vh-41px)] bg-gradient-to-br from-amber-50/50 via-orange-50/30 to-slate-100 flex items-center justify-center p-4 sm:p-6">
      <div className="w-full max-w-4xl space-y-8">
        {/* Header */}
        <div className="text-center space-y-2">
          <div className="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-bold border border-orange-200 shadow-2xs">
            <Sparkles className="w-3.5 h-3.5 text-orange-600" />
            <span>etoKos Demo Authentication</span>
          </div>
          <h1 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Pilih Peran untuk Memulai <span className="text-orange-600">Demo</span>
          </h1>
          <p className="text-slate-600 text-sm max-w-lg mx-auto">
            Sistem manajemen kos-kosan terintegrasi untuk pasar Indonesia. Pilih salah satu profil di bawah ini untuk menjelajahi aplikasi tanpa perlu registrasi manual.
          </p>
        </div>

        {/* 3 Role Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {/* OWNER CARD */}
          <div
            onClick={() => handleLoginAs("owner")}
            className="cursor-pointer bg-white rounded-2xl p-6 border-2 border-slate-200 hover:border-orange-500 hover:shadow-xl transition-all transform hover:-translate-y-1 flex flex-col justify-between group"
          >
            <div className="space-y-4">
              <div className="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition">
                <ShieldCheck className="w-6 h-6" />
              </div>
              <div>
                <h3 className="text-lg font-bold text-slate-900 group-hover:text-orange-600 transition">
                  Owner / Pengelola
                </h3>
                <p className="text-xs text-slate-500 mt-1">
                  Akses penuh operasional & keuangan seluruh properti kos.
                </p>
              </div>

              <div className="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Dashboard Okupansi & Pendapatan</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Denah Kamar & Manajemen Kontrak</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Tagihan Massal & Verifikasi Bayar</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Kanban Keluhan & Akuntansi Laba Rugi</span>
                </div>
              </div>
            </div>

            <div className="pt-6">
              <button
                type="button"
                className="w-full py-2.5 px-4 rounded-xl bg-orange-600 text-white font-semibold text-xs flex items-center justify-center space-x-2 group-hover:bg-orange-700 transition shadow-sm"
              >
                <span>Masuk sebagai Owner</span>
                <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition" />
              </button>
            </div>
          </div>

          {/* TENANT CARD */}
          <div
            onClick={() => handleLoginAs("penghuni")}
            className="cursor-pointer bg-white rounded-2xl p-6 border-2 border-slate-200 hover:border-sky-500 hover:shadow-xl transition-all transform hover:-translate-y-1 flex flex-col justify-between group relative overflow-hidden"
          >
            <div className="absolute top-3 right-3">
              <span className="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                Alur Wow Disini!
              </span>
            </div>

            <div className="space-y-4">
              <div className="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition">
                <UserCheck className="w-6 h-6" />
              </div>
              <div>
                <h3 className="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition">
                  Penghuni Aktif
                </h3>
                <p className="text-xs text-slate-500 mt-1">
                  Budi Santoso — Kamar #102 di OMYU Living BSD.
                </p>
              </div>

              <div className="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-sky-500 flex-shrink-0" />
                  <span>Lihat & Bayar Tagihan Sewa (QRIS/Transfer)</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-sky-500 flex-shrink-0" />
                  <span>Unggah Bukti Bayar Instan</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-sky-500 flex-shrink-0" />
                  <span>Lapor Keluhan (AC Bocor, dll)</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-sky-500 flex-shrink-0" />
                  <span>Pantau Deposit & Pengumuman Kos</span>
                </div>
              </div>
            </div>

            <div className="pt-6">
              <button
                type="button"
                className="w-full py-2.5 px-4 rounded-xl bg-sky-600 text-white font-semibold text-xs flex items-center justify-center space-x-2 group-hover:bg-sky-700 transition shadow-sm"
              >
                <span>Masuk sebagai Penghuni</span>
                <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition" />
              </button>
            </div>
          </div>

          {/* VISITOR CARD */}
          <div
            onClick={() => handleLoginAs("pengunjung")}
            className="cursor-pointer bg-white rounded-2xl p-6 border-2 border-slate-200 hover:border-emerald-500 hover:shadow-xl transition-all transform hover:-translate-y-1 flex flex-col justify-between group"
          >
            <div className="space-y-4">
              <div className="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                <Globe className="w-6 h-6" />
              </div>
              <div>
                <h3 className="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition">
                  Pengunjung / Calon Penyewa
                </h3>
                <p className="text-xs text-slate-500 mt-1">
                  Eksplorasi listing kos publik & ajukan sewa kamar.
                </p>
              </div>

              <div className="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Katalog Kos OMYU BSD & Griya Gejayan</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Filter Harga, Tipe Kos & Fasilitas</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Ketersediaan Kamar Real-time</span>
                </div>
                <div className="flex items-center space-x-2">
                  <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                  <span>Chat WhatsApp & Formulir Booking</span>
                </div>
              </div>
            </div>

            <div className="pt-6">
              <button
                type="button"
                className="w-full py-2.5 px-4 rounded-xl bg-emerald-600 text-white font-semibold text-xs flex items-center justify-center space-x-2 group-hover:bg-emerald-700 transition shadow-sm"
              >
                <span>Masuk sebagai Pengunjung</span>
                <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition" />
              </button>
            </div>
          </div>
        </div>

        {/* Demo Footer Note */}
        <div className="p-4 rounded-xl bg-white/70 border border-slate-200 text-center text-xs text-slate-500">
          💡 <strong>Tip Demo:</strong> Anda juga dapat beralih peran kapan saja dengan mengklik tombol cepat di bilah hitam bagian atas (Top Bar).
        </div>
      </div>
    </div>
  );
}
