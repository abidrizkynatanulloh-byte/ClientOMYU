"use client";

import React, { useState } from "react";
import { useKosStore } from "@/lib/store";
import { useRouter } from "next/navigation";
import {
  Sparkles,
  CheckCircle2,
  ArrowRight,
  Wrench,
  DollarSign,
  AlertCircle,
  X,
  Play,
  RotateCcw,
} from "lucide-react";

export function WowFlowModal({ isOpen, onClose }: { isOpen: boolean; onClose: () => void }) {
  const {
    tickets,
    setRole,
    assignTechnician,
    resolveTicketWithCost,
    resetDemoData,
  } = useKosStore();
  const router = useRouter();
  const [activeStep, setActiveStep] = useState(1);
  const [isAutomating, setIsAutomating] = useState(false);
  const [successMessage, setSuccessMessage] = useState("");

  if (!isOpen) return null;

  // Find ticket 1 (Budi Santoso - AC Bocor Kamar 102)
  const acTicket = tickets.find((t) => t.id === "tkt-1" || t.category === "AC");

  const handleRunAutoSim = () => {
    setIsAutomating(true);
    setSuccessMessage("");

    setTimeout(() => {
      // Step 2: Assign
      if (acTicket) {
        assignTechnician(acTicket.id, {
          name: "Pak Joko (Teknisi Spesialis AC)",
          phone: "081299887711",
          speciality: "Pendingin Udara & Listrik",
        });
      }

      setTimeout(() => {
        // Step 3: Resolve with Rp 350.000
        if (acTicket) {
          resolveTicketWithCost(
            acTicket.id,
            350000,
            "Pembersihan pipa drainase blower & isi freon R32. Kondisi dingin normal."
          );
        }
        setIsAutomating(false);
        setRole("owner");
        setSuccessMessage(
          "Berhasil! Biaya perbaikan Rp 350.000 telah otomatis masuk ke Akuntansi & Laba Rugi berubah!"
        );
      }, 800);
    }, 600);
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
      <div className="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl overflow-hidden border border-amber-200 animate-in fade-in zoom-in-95 duration-200">
        {/* Header */}
        <div className="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 px-6 py-5 text-white flex items-center justify-between">
          <div className="flex items-center space-x-3">
            <div className="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
              <Sparkles className="w-6 h-6 text-yellow-200" />
            </div>
            <div>
              <h2 className="text-xl font-bold">Panduan Alur "Wow" etoKos</h2>
              <p className="text-xs text-amber-100">
                Siklus Terintegrasi: Penghuni Lapor → Owner Tugaskan → Selesai → Pengeluaran Otomatis
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Content */}
        <div className="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
          {successMessage && (
            <div className="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-3">
              <CheckCircle2 className="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" />
              <div>
                <p className="font-semibold text-sm">{successMessage}</p>
                <div className="mt-2 flex space-x-2">
                  <button
                    onClick={() => {
                      onClose();
                      router.push("/owner/akuntansi");
                    }}
                    className="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition"
                  >
                    Buka Laporan Akuntansi Sekarang →
                  </button>
                </div>
              </div>
            </div>
          )}

          {/* Stepper overview */}
          <div className="grid grid-cols-4 gap-2">
            {[
              { num: 1, title: "Lapor AC", sub: "Penghuni" },
              { num: 2, title: "Tugaskan", sub: "Owner" },
              { num: 3, title: "Selesai Biaya", sub: "Teknisi" },
              { num: 4, title: "Laba Rugi", sub: "Akuntansi" },
            ].map((st) => (
              <div
                key={st.num}
                onClick={() => setActiveStep(st.num)}
                className={`cursor-pointer rounded-xl p-3 border text-center transition ${
                  activeStep === st.num
                    ? "border-orange-500 bg-orange-50/70 shadow-sm"
                    : "border-slate-200 bg-slate-50/50 hover:bg-slate-100/60"
                }`}
              >
                <div
                  className={`w-7 h-7 mx-auto rounded-full flex items-center justify-center text-xs font-bold mb-1 ${
                    activeStep === st.num
                      ? "bg-orange-500 text-white"
                      : "bg-slate-200 text-slate-700"
                  }`}
                >
                  {st.num}
                </div>
                <p className="text-xs font-semibold text-slate-900">{st.title}</p>
                <p className="text-[10px] text-slate-500">{st.sub}</p>
              </div>
            ))}
          </div>

          {/* Active Step Details */}
          <div className="p-5 rounded-xl border border-slate-200 bg-slate-50/60 space-y-4">
            {activeStep === 1 && (
              <div className="space-y-3">
                <div className="flex items-center space-x-2 text-orange-600 font-semibold text-sm">
                  <AlertCircle className="w-4 h-4" />
                  <span>Langkah 1: Penghuni Melaporkan Keluhan AC</span>
                </div>
                <p className="text-sm text-slate-600 leading-relaxed">
                  Penghuni kamar 102 (<strong>Budi Santoso</strong>) mengalami masalah AC bocor di kamar tidurnya. Di Portal Penghuni, ia dapat mengisi form laporan keluhan lengkap dengan kategori, prioritas, dan foto bukti.
                </p>
                <div className="p-3 bg-white rounded-lg border border-slate-200 text-xs text-slate-700 space-y-1">
                  <p><strong>Status Saat Ini:</strong> {acTicket ? `Tiket #${acTicket.ticketNumber} (${acTicket.status})` : "Tiket Baru"}</p>
                  <p><strong>Judul:</strong> AC Bocor & Netes ke Meja Kerja</p>
                  <p><strong>Prioritas:</strong> Tinggi</p>
                </div>
                <div className="flex space-x-2 pt-2">
                  <button
                    onClick={() => {
                      setRole("penghuni");
                      onClose();
                      router.push("/penghuni/keluhan");
                    }}
                    className="px-4 py-2 bg-orange-500 text-white text-xs font-semibold rounded-lg hover:bg-orange-600 transition flex items-center space-x-1.5"
                  >
                    <span>Masuk Portal Penghuni & Lihat Form</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            )}

            {activeStep === 2 && (
              <div className="space-y-3">
                <div className="flex items-center space-x-2 text-blue-600 font-semibold text-sm">
                  <Wrench className="w-4 h-4" />
                  <span>Langkah 2: Owner Menugaskan Teknisi via Kanban</span>
                </div>
                <p className="text-sm text-slate-600 leading-relaxed">
                  Owner melihat tiket masuk di kolom <strong>"Baru"</strong> pada papan Kanban Tiket. Owner menugaskan <strong>Pak Joko (Teknisi AC)</strong>, dan tiket otomatis berpindah status ke <strong>"Diproses"</strong>.
                </p>
                <div className="flex space-x-2 pt-2">
                  <button
                    onClick={() => {
                      setRole("owner");
                      onClose();
                      router.push("/owner/keluhan");
                    }}
                    className="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition flex items-center space-x-1.5"
                  >
                    <span>Buka Papan Kanban Keluhan (Owner)</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            )}

            {activeStep === 3 && (
              <div className="space-y-3">
                <div className="flex items-center space-x-2 text-emerald-600 font-semibold text-sm">
                  <CheckCircle2 className="w-4 h-4" />
                  <span>Langkah 3: Pengerjaan Selesai & Input Biaya Perbaikan</span>
                </div>
                <p className="text-sm text-slate-600 leading-relaxed">
                  Setelah perbaikan selesai, Owner memindahkan tiket ke kolom <strong>"Selesai"</strong> dan memasukkan biaya perbaikan nyata (misal: <strong>Rp 350.000</strong> untuk cuci AC dan isi freon).
                </p>
                <div className="p-3 bg-amber-50 rounded-lg border border-amber-200 text-xs text-amber-800">
                  <p className="font-semibold">⚡ Mekanisme Otomatis:</p>
                  <p>Sistem tidak hanya menutup tiket, tetapi langsung membuat transaksi pengeluaran di Akuntansi tanpa input ganda!</p>
                </div>
              </div>
            )}

            {activeStep === 4 && (
              <div className="space-y-3">
                <div className="flex items-center space-x-2 text-purple-600 font-semibold text-sm">
                  <DollarSign className="w-4 h-4" />
                  <span>Langkah 4: Laba Rugi & Arus Kas Berubah Otomatis!</span>
                </div>
                <p className="text-sm text-slate-600 leading-relaxed">
                  Biaya perbaikan Rp 350.000 langsung tercatat di <strong>Beban Operasional: Perbaikan & Perawatan</strong>. Nilai Laba Bersih bulan berjalan otomatis terkalkulasi ulang dan tercatat di Log Aktivitas!
                </p>
                <div className="flex space-x-2 pt-2">
                  <button
                    onClick={() => {
                      setRole("owner");
                      onClose();
                      router.push("/owner/akuntansi");
                    }}
                    className="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-lg hover:bg-purple-700 transition flex items-center space-x-1.5"
                  >
                    <span>Cek Modul Akuntansi & Laba Rugi</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            )}
          </div>

          {/* Quick 1-click execution */}
          <div className="p-4 rounded-xl bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <p className="text-xs font-bold text-orange-950 flex items-center space-x-1">
                <Sparkles className="w-3.5 h-3.5 text-orange-500" />
                <span>Simulasi Instan 1-Klik</span>
              </p>
              <p className="text-[11px] text-orange-800">
                Jalankan siklus lengkap (Tugaskan → Selesai Rp 350.000 → Masuk Akuntansi) dalam 2 detik.
              </p>
            </div>
            <div className="flex items-center space-x-2">
              <button
                onClick={handleRunAutoSim}
                disabled={isAutomating}
                className="px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-bold rounded-lg shadow-sm hover:from-orange-600 hover:to-amber-600 transition flex items-center space-x-1.5 disabled:opacity-50"
              >
                <Play className="w-3.5 h-3.5 fill-current" />
                <span>{isAutomating ? "Memproses..." : "Jalankan Alur Sekarang"}</span>
              </button>
            </div>
          </div>
        </div>

        {/* Footer */}
        <div className="bg-slate-100 px-6 py-3 border-t border-slate-200 flex items-center justify-between">
          <button
            onClick={() => {
              resetDemoData();
              setSuccessMessage("Data demo telah direset ke awal.");
            }}
            className="text-xs text-slate-500 hover:text-slate-800 flex items-center space-x-1 transition"
          >
            <RotateCcw className="w-3.5 h-3.5" />
            <span>Reset Data Demo</span>
          </button>
          <button
            onClick={onClose}
            className="px-4 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-medium rounded-lg transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  );
}
