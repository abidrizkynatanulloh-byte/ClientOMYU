"use client";

import React, { useState } from "react";
import Link from "next/link";
import { useKosStore } from "@/lib/store";
import {
  formatRupiah,
  getPriorityMeta,
  getTicketStatusMeta,
} from "@/lib/formatters";
import { Ticket, TicketStatus } from "@/types";
import {
  Wrench,
  UserCheck,
  CheckCircle2,
  MessageSquare,
  Sparkles,
  X,
  ArrowRight,
  DollarSign,
  Send,
  Camera,
} from "lucide-react";

const TECHNICIANS = [
  {
    name: "Pak Joko (Spesialis AC & Listrik)",
    phone: "081299887711",
    speciality: "Pendingin Udara & Kelistrikan",
  },
  {
    name: "Pak Slamet (Plumbing & Bangunan)",
    phone: "081255443322",
    speciality: "Pipa Air, Kran & Sipil",
  },
  {
    name: "Mas Doni (IT & Jaringan WiFi)",
    phone: "085677889900",
    speciality: "Router, Fiber Optic & CCTV",
  },
  {
    name: "Mas Bowo (Staf Umum Kos)",
    phone: "081399001122",
    speciality: "Perawatan Umum & Kebersihan",
  },
];

export default function OwnerKeluhanPage() {
  const {
    selectedPropertyId,
    tickets,
    assignTechnician,
    resolveTicketWithCost,
    addTicketComment,
  } = useKosStore();

  const [selectedTicket, setSelectedTicket] = useState<Ticket | null>(null);
  const [assignModalTicket, setAssignModalTicket] = useState<Ticket | null>(null);
  const [resolveModalTicket, setResolveModalTicket] = useState<Ticket | null>(null);

  const [chosenTechIdx, setChosenTechIdx] = useState(0);
  const [repairCostInput, setRepairCostInput] = useState<number>(350000);
  const [completionNoteInput, setCompletionNoteInput] = useState<string>(
    "Cuci evaporator, perbaiki saluran pembuangan kondensasi & tambah freon R32. AC kembali dingin normal."
  );
  const [commentInput, setCommentInput] = useState("");
  const [wowBanner, setWowBanner] = useState<{
    title: string;
    cost: number;
  } | null>(null);

  const filteredTickets = tickets.filter(
    (t) => selectedPropertyId === "all" || t.propertyId === selectedPropertyId
  );

  const columns: { status: TicketStatus; title: string; badgeBg: string }[] = [
    { status: "BARU", title: "Tiket Baru Masuk", badgeBg: "bg-amber-500" },
    { status: "DIPROSES", title: "Sedang Dikerjakan Teknisi", badgeBg: "bg-blue-500" },
    { status: "SELESAI", title: "Selesai Diperbaiki", badgeBg: "bg-emerald-500" },
  ];

  const handleAssignSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!assignModalTicket) return;
    const tech = TECHNICIANS[chosenTechIdx];
    assignTechnician(assignModalTicket.id, tech);
    setAssignModalTicket(null);
  };

  const handleResolveSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!resolveModalTicket) return;
    const cost = Math.max(0, Number(repairCostInput));
    const res = resolveTicketWithCost(
      resolveModalTicket.id,
      cost,
      completionNoteInput || "Perbaikan selesai."
    );
    if (res.success && cost > 0) {
      setWowBanner({
        title: resolveModalTicket.title,
        cost,
      });
    }
    setResolveModalTicket(null);
  };

  const handleSendComment = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedTicket || !commentInput.trim()) return;
    addTicketComment(selectedTicket.id, commentInput);
    setCommentInput("");
    // Refresh selectedTicket view
    const updated = tickets.find((t) => t.id === selectedTicket.id);
    if (updated) setSelectedTicket(updated);
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
            Papan Kanban Tiket Keluhan & Perbaikan
          </h1>
          <p className="text-xs text-slate-500 mt-0.5">
            Kelola laporan kerusakan dari penghuni, tugaskan teknisi, dan catat biaya perbaikan otomatis ke Akuntansi.
          </p>
        </div>
      </div>

      {/* WOW FLOW CELEBRATION BANNER */}
      {wowBanner && (
        <div className="p-5 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-in fade-in duration-300">
          <div className="flex items-start space-x-3">
            <div className="p-2.5 bg-white/20 rounded-xl">
              <Sparkles className="w-6 h-6 text-yellow-300" />
            </div>
            <div>
              <span className="px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-bold uppercase tracking-wider">
                Alur "Wow" Terintegrasi Berhasil!
              </span>
              <h3 className="text-base font-extrabold mt-1">
                Tiket Selesai & Biaya {formatRupiah(wowBanner.cost)} Otomatis Masuk Pengeluaran!
              </h3>
              <p className="text-xs text-emerald-100 mt-0.5">
                Perbaikan "{wowBanner.title}" telah tercatat di modul Akuntansi dan langsung memperbarui Laporan Laba Rugi bulan ini.
              </p>
            </div>
          </div>

          <div className="flex items-center space-x-2 self-end sm:self-center">
            <Link
              href="/owner/akuntansi"
              className="px-4 py-2.5 rounded-xl bg-white text-emerald-800 font-bold text-xs hover:bg-emerald-50 transition shadow flex items-center space-x-1.5 whitespace-nowrap"
            >
              <span>Lihat Perubahan Laba Rugi</span>
              <ArrowRight className="w-4 h-4" />
            </Link>
            <button
              onClick={() => setWowBanner(null)}
              className="p-1.5 text-white/80 hover:text-white"
            >
              <X className="w-4 h-4" />
            </button>
          </div>
        </div>
      )}

      {/* 3-Column Kanban Board */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {columns.map((col) => {
          const colTickets = filteredTickets.filter((t) => t.status === col.status);

          return (
            <div
              key={col.status}
              className="bg-slate-100/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-4 min-h-[540px]"
            >
              {/* Column Header */}
              <div className="flex items-center justify-between px-1">
                <div className="flex items-center space-x-2">
                  <span className={`w-2.5 h-2.5 rounded-full ${col.badgeBg}`} />
                  <h2 className="text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    {col.title}
                  </h2>
                </div>
                <span className="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-2xs">
                  {colTickets.length}
                </span>
              </div>

              {/* Cards */}
              <div className="space-y-3 flex-1">
                {colTickets.map((tkt) => {
                  const pMeta = getPriorityMeta(tkt.priority);
                  const isWowCandidate = tkt.id === "tkt-1" || tkt.category === "AC";

                  return (
                    <div
                      key={tkt.id}
                      className={`bg-white rounded-xl p-4 border shadow-2xs hover:shadow-md transition flex flex-col justify-between space-y-3 ${
                        isWowCandidate && tkt.status !== "SELESAI"
                          ? "border-orange-400 ring-1 ring-orange-300"
                          : "border-slate-200/90"
                      }`}
                    >
                      <div className="space-y-2.5">
                        <div className="flex items-center justify-between">
                          <span className="text-[10px] font-bold text-slate-400">
                            {tkt.ticketNumber} • {tkt.createdAt.split(" ")[0]}
                          </span>
                          <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${pMeta.bg}`}>
                            {pMeta.label}
                          </span>
                        </div>

                        <div>
                          <span className="inline-block px-2 py-0.5 rounded bg-orange-50 text-orange-700 text-[10px] font-semibold mb-1">
                            {tkt.category} • Kamar #{tkt.roomNumber}
                          </span>
                          <h3
                            onClick={() => setSelectedTicket(tkt)}
                            className="text-sm font-bold text-slate-900 hover:text-orange-600 cursor-pointer"
                          >
                            {tkt.title}
                          </h3>
                          <p className="text-xs text-slate-500 line-clamp-2 mt-1">
                            {tkt.description}
                          </p>
                        </div>

                        {tkt.imageUrl && (
                          <div
                            onClick={() => setSelectedTicket(tkt)}
                            className="relative h-28 rounded-lg overflow-hidden cursor-pointer border border-slate-100"
                          >
                            <img
                              src={tkt.imageUrl}
                              alt={tkt.title}
                              className="w-full h-full object-cover hover:scale-105 transition"
                            />
                            <span className="absolute bottom-1.5 right-1.5 px-2 py-0.5 rounded bg-black/60 text-white text-[10px] flex items-center space-x-1">
                              <Camera className="w-3 h-3" />
                              <span>Foto Bukti</span>
                            </span>
                          </div>
                        )}

                        {/* Assigned Technician */}
                        {tkt.assignedTechnician && (
                          <div className="p-2.5 rounded-lg bg-blue-50/70 border border-blue-200/70 text-[11px] text-blue-950">
                            <p className="font-bold flex items-center space-x-1">
                              <Wrench className="w-3 h-3 text-blue-600" />
                              <span>Teknisi: {tkt.assignedTechnician.name}</span>
                            </p>
                          </div>
                        )}

                        {/* Completed Cost Info */}
                        {tkt.status === "SELESAI" && (
                          <div className="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px] text-emerald-900 space-y-1">
                            <p className="font-bold">
                              Biaya Perbaikan: {formatRupiah(tkt.repairCost || 0)}
                            </p>
                            {tkt.completionNote && (
                              <p className="text-[10px] text-emerald-700">{tkt.completionNote}</p>
                            )}
                          </div>
                        )}
                      </div>

                      {/* Card Footer & Actions */}
                      <div className="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                        <button
                          onClick={() => setSelectedTicket(tkt)}
                          className="text-slate-500 hover:text-slate-800 font-medium flex items-center space-x-1"
                        >
                          <MessageSquare className="w-3.5 h-3.5" />
                          <span>{tkt.comments.length} Komentar</span>
                        </button>

                        {tkt.status === "BARU" && (
                          <button
                            onClick={() => setAssignModalTicket(tkt)}
                            className="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] flex items-center space-x-1 transition shadow-2xs"
                          >
                            <UserCheck className="w-3.5 h-3.5" />
                            <span>Tugaskan Teknisi</span>
                          </button>
                        )}

                        {tkt.status === "DIPROSES" && (
                          <button
                            onClick={() => {
                              setResolveModalTicket(tkt);
                              setRepairCostInput(tkt.category === "AC" ? 350000 : 125000);
                            }}
                            className="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] flex items-center space-x-1 transition shadow-2xs"
                          >
                            <CheckCircle2 className="w-3.5 h-3.5" />
                            <span>Tandai Selesai & Biaya</span>
                          </button>
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          );
        })}
      </div>

      {/* Modal 1: Assign Technician */}
      {assignModalTicket && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-blue-600 px-6 py-4 text-white flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <Wrench className="w-5 h-5" />
                <h3 className="font-bold text-sm">Tugaskan Teknisi Perbaikan</h3>
              </div>
              <button onClick={() => setAssignModalTicket(null)}>
                <X className="w-5 h-5 text-white/80 hover:text-white" />
              </button>
            </div>

            <form onSubmit={handleAssignSubmit} className="p-6 space-y-4 text-xs">
              <div className="p-3 rounded-xl bg-slate-50 border border-slate-200">
                <p className="font-bold text-slate-900">
                  Kamar #{assignModalTicket.roomNumber} — {assignModalTicket.title}
                </p>
                <p className="text-slate-500 mt-0.5">Pelapor: {assignModalTicket.tenantName}</p>
              </div>

              <div className="space-y-2">
                <label className="font-bold text-slate-700 block">Pilih Teknisi Tersedia:</label>
                {TECHNICIANS.map((tech, idx) => (
                  <label
                    key={idx}
                    className={`flex items-start space-x-3 p-3 rounded-xl border cursor-pointer transition ${
                      chosenTechIdx === idx
                        ? "border-blue-600 bg-blue-50/50"
                        : "border-slate-200 hover:bg-slate-50"
                    }`}
                  >
                    <input
                      type="radio"
                      name="tech"
                      checked={chosenTechIdx === idx}
                      onChange={() => setChosenTechIdx(idx)}
                      className="mt-1"
                    />
                    <div>
                      <p className="font-bold text-slate-900">{tech.name}</p>
                      <p className="text-[11px] text-slate-500">
                        Spesialisasi: {tech.speciality} • WA: {tech.phone}
                      </p>
                    </div>
                  </label>
                ))}
              </div>

              <div className="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                <button
                  type="button"
                  onClick={() => setAssignModalTicket(null)}
                  className="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold"
                >
                  Tugaskan & Proses Tiket
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal 2: Resolve Ticket & Auto-Record Expense (THE WOW FLOW) */}
      {resolveModalTicket && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-emerald-600 px-6 py-4 text-white flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <CheckCircle2 className="w-5 h-5" />
                <h3 className="font-bold text-sm">Selesaikan Perbaikan & Catat Biaya</h3>
              </div>
              <button onClick={() => setResolveModalTicket(null)}>
                <X className="w-5 h-5 text-white/80 hover:text-white" />
              </button>
            </div>

            <form onSubmit={handleResolveSubmit} className="p-6 space-y-4 text-xs">
              <div className="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px] flex items-start space-x-2">
                <Sparkles className="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" />
                <div>
                  <strong>Integrasi Otomatis ke Akuntansi:</strong> Biaya perbaikan yang Anda masukkan di bawah ini akan otomatis tercatat sebagai <em>Pengeluaran Operasional</em> dan mengurangi Laba Bersih bulan berjalan!
                </div>
              </div>

              <div>
                <p className="font-bold text-slate-900">
                  {resolveModalTicket.ticketNumber} — {resolveModalTicket.title}
                </p>
                <p className="text-slate-500">
                  Kamar #{resolveModalTicket.roomNumber} • Teknisi:{" "}
                  {resolveModalTicket.assignedTechnician?.name || "Pak Joko"}
                </p>
              </div>

              <div className="space-y-1">
                <label className="font-bold text-slate-700">
                  Biaya Jasa & Suku Cadang Perbaikan (Rp) *
                </label>
                <input
                  type="number"
                  min={0}
                  required
                  value={repairCostInput}
                  onChange={(e) => setRepairCostInput(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg font-bold text-sm text-emerald-700"
                />
              </div>

              <div className="space-y-1">
                <label className="font-bold text-slate-700">Catatan Penyelesaian Pekerjaan *</label>
                <textarea
                  rows={3}
                  required
                  value={completionNoteInput}
                  onChange={(e) => setCompletionNoteInput(e.target.value)}
                  className="w-full px-3 py-2 border rounded-lg"
                />
              </div>

              <div className="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                <button
                  type="button"
                  onClick={() => setResolveModalTicket(null)}
                  className="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-sm"
                >
                  Selesai & Masuk Pengeluaran
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal 3: Ticket Detail & Comments */}
      {selectedTicket && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-slate-900 px-6 py-4 text-white flex items-center justify-between">
              <div>
                <span className="text-[10px] text-orange-400 font-bold uppercase">
                  {selectedTicket.ticketNumber} • Kamar #{selectedTicket.roomNumber}
                </span>
                <h3 className="font-bold text-sm">{selectedTicket.title}</h3>
              </div>
              <button onClick={() => setSelectedTicket(null)}>
                <X className="w-5 h-5 text-slate-400 hover:text-white" />
              </button>
            </div>

            <div className="p-6 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
              <p className="text-slate-700 leading-relaxed">{selectedTicket.description}</p>

              {selectedTicket.imageUrl && (
                <img
                  src={selectedTicket.imageUrl}
                  alt={selectedTicket.title}
                  className="w-full h-48 object-cover rounded-xl border border-slate-200"
                />
              )}

              {/* Comments Thread */}
              <div className="space-y-2 pt-3 border-t border-slate-100">
                <h4 className="font-bold text-slate-800">Riwayat Diskusi & Penanganan:</h4>
                <div className="space-y-2 max-h-44 overflow-y-auto">
                  {selectedTicket.comments.map((c) => (
                    <div key={c.id} className="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                      <div className="flex justify-between font-bold text-slate-900 mb-1">
                        <span>{c.authorName}</span>
                        <span className="text-[10px] text-slate-400">{c.createdAt}</span>
                      </div>
                      <p className="text-slate-600">{c.text}</p>
                    </div>
                  ))}
                </div>

                <form onSubmit={handleSendComment} className="flex space-x-2 pt-2">
                  <input
                    type="text"
                    placeholder="Tulis tanggapan atau update pengerjaan..."
                    value={commentInput}
                    onChange={(e) => setCommentInput(e.target.value)}
                    className="flex-1 px-3 py-2 border rounded-xl outline-none focus:border-orange-500"
                  />
                  <button
                    type="submit"
                    className="px-4 py-2 rounded-xl bg-orange-600 text-white font-bold flex items-center space-x-1"
                  >
                    <Send className="w-3.5 h-3.5" />
                    <span>Kirim</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
