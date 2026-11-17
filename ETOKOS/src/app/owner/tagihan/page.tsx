"use client";

import React, { useState } from "react";
import { useKosStore } from "@/lib/store";
import {
  formatRupiah,
  formatTanggal,
  getBillStatusMeta,
  buildWhatsAppLink,
} from "@/lib/formatters";
import { Bill, BillStatus } from "@/types";
import {
  Receipt,
  Sparkles,
  Download,
  CheckCircle2,
  XCircle,
  MessageCircle,
  Eye,
  Edit3,
  Printer,
  X,
  FileText,
  Clock,
  ShieldCheck,
} from "lucide-react";

export default function OwnerTagihanPage() {
  const {
    selectedPropertyId,
    properties,
    bills,
    tenants,
    generateMonthlyBills,
    verifyPayment,
    correctBill,
  } = useKosStore();

  const [monthFilter, setMonthFilter] = useState<string>("2026-06");
  const [statusFilter, setStatusFilter] = useState<BillStatus | "ALL">("ALL");
  const [selectedInvoice, setSelectedInvoice] = useState<Bill | null>(null);
  const [correctionBillModal, setCorrectionBillModal] = useState<Bill | null>(null);
  const [correctionAmount, setCorrectionAmount] = useState<number>(0);
  const [correctionReason, setCorrectionReason] = useState<string>("");
  const [bannerMsg, setBannerMsg] = useState<string>("");

  const filteredBills = bills.filter((b) => {
    const matchProp = selectedPropertyId === "all" || b.propertyId === selectedPropertyId;
    const matchMonth = monthFilter === "ALL" || b.billingMonth === monthFilter;
    const matchStatus = statusFilter === "ALL" || b.status === statusFilter;
    return matchProp && matchMonth && matchStatus;
  });

  const handleBulkGenerate = () => {
    const res = generateMonthlyBills(monthFilter === "ALL" ? "2026-06" : monthFilter, selectedPropertyId);
    setBannerMsg(res.message);
  };

  const handleVerify = (billId: string, approved: boolean) => {
    verifyPayment(
      billId,
      approved,
      approved ? "Transfer diterima dan sesuai mutasi rekening" : "Bukti transfer buram / belum masuk"
    );
    setBannerMsg(
      approved
        ? "Bukti pembayaran berhasil diverifikasi LUNAS! Pemasukan otomatis tercatat di Akuntansi."
        : "Bukti pembayaran ditolak. Penghuni akan diminta mengunggah ulang."
    );
    if (selectedInvoice?.id === billId) {
      setSelectedInvoice(null);
    }
  };

  const handleCorrectionSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!correctionBillModal || !correctionReason.trim()) return;
    correctBill(correctionBillModal.id, Number(correctionAmount), correctionReason);
    setBannerMsg(
      `Koreksi tagihan ${correctionBillModal.invoiceNumber} berhasil disimpan dengan catatan audit.`
    );
    setCorrectionBillModal(null);
    setCorrectionAmount(0);
    setCorrectionReason("");
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
            Manajemen Tagihan & Pembayaran
          </h1>
          <p className="text-xs text-slate-500 mt-0.5">
            Generate tagihan bulanan massal, verifikasi bukti bayar, koreksi teraudit, dan kirim pengingat WhatsApp.
          </p>
        </div>

        <button
          onClick={handleBulkGenerate}
          className="px-4 py-2.5 rounded-xl bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-2 self-start"
        >
          <Sparkles className="w-4 h-4 text-yellow-200" />
          <span>Generate Tagihan Massal ({monthFilter === "ALL" ? "Juni 2026" : monthFilter})</span>
        </button>
      </div>

      {bannerMsg && (
        <div className="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0" />
            <span className="font-semibold">{bannerMsg}</span>
          </div>
          <button onClick={() => setBannerMsg("")}>
            <X className="w-4 h-4 text-emerald-600" />
          </button>
        </div>
      )}

      {/* Filter Bar */}
      <div className="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap items-center justify-between gap-4 text-xs">
        <div className="flex items-center space-x-2">
          <span className="font-semibold text-slate-600">Periode Tagihan:</span>
          <select
            value={monthFilter}
            onChange={(e) => setMonthFilter(e.target.value)}
            className="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 font-bold text-slate-800"
          >
            <option value="2026-06">Juni 2026 (Berjalan)</option>
            <option value="2026-05">Mei 2026</option>
            <option value="2026-04">April 2026</option>
            <option value="2026-03">Maret 2026</option>
            <option value="2026-02">Februari 2026</option>
            <option value="2026-01">Januari 2026</option>
            <option value="ALL">Semua Periode (6 Bulan)</option>
          </select>
        </div>

        <div className="flex flex-wrap items-center gap-1.5">
          {[
            { id: "ALL", label: "Semua Status" },
            { id: "BELUM_BAYAR", label: "Belum Bayar" },
            { id: "TELAT", label: "Telat / Denda" },
            { id: "SEBAGIAN", label: "Sebagian" },
            { id: "LUNAS", label: "Lunas" },
          ].map((st) => (
            <button
              key={st.id}
              onClick={() => setStatusFilter(st.id as any)}
              className={`px-3 py-1.5 rounded-lg font-semibold transition ${
                statusFilter === st.id
                  ? "bg-orange-600 text-white"
                  : "bg-slate-100 text-slate-600 hover:bg-slate-200"
              }`}
            >
              {st.label}
            </button>
          ))}
        </div>
      </div>

      {/* Invoices Table */}
      <div className="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse text-xs">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                <th className="py-3.5 px-4">No. Invoice</th>
                <th className="py-3.5 px-4">Penghuni & Kamar</th>
                <th className="py-3.5 px-4">Periode & Jatuh Tempo</th>
                <th className="py-3.5 px-4">Rincian & Denda</th>
                <th className="py-3.5 px-4">Total Tagihan</th>
                <th className="py-3.5 px-4">Status & Bukti</th>
                <th className="py-3.5 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filteredBills.map((bill) => {
                const statusMeta = getBillStatusMeta(bill.status);
                const tenant = tenants.find((t) => t.id === bill.tenantId);
                const pendingProof = bill.paymentProofs.find((p) => p.status === "PENDING");

                const waReminderText = `Halo Kak *${bill.tenantName}* (Kamar #${bill.roomNumber}),\n\nKami mengingatkan tagihan sewa kos periode *${bill.periodName}* dengan nomor *${bill.invoiceNumber}* sebesar *${formatRupiah(
                  bill.remainingAmount
                )}* (Jatuh tempo: ${formatTanggal(
                  bill.dueDate
                )}).\n\nMohon melakukan pembayaran dan mengunggah bukti transfer melalui Portal Penghuni etoKos. Terima kasih! 🙏`;

                const waLink = buildWhatsAppLink(tenant?.phone || "081234567890", waReminderText);

                return (
                  <tr key={bill.id} className="hover:bg-slate-50/70 transition">
                    <td className="py-3.5 px-4 font-bold text-slate-900">
                      <div>{bill.invoiceNumber}</div>
                      {bill.paymentCorrectionNote && (
                        <span className="inline-block mt-1 px-1.5 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 text-[10px]">
                          {bill.paymentCorrectionNote}
                        </span>
                      )}
                    </td>

                    <td className="py-3.5 px-4">
                      <div className="font-bold text-slate-900">{bill.tenantName}</div>
                      <div className="text-[11px] text-slate-500">Kamar #{bill.roomNumber}</div>
                    </td>

                    <td className="py-3.5 px-4">
                      <div className="font-semibold text-slate-800">{bill.periodName}</div>
                      <div className="text-[11px] text-slate-500">
                        Tempo: {formatTanggal(bill.dueDate)}
                      </div>
                    </td>

                    <td className="py-3.5 px-4">
                      <div className="text-slate-600">
                        Pokok: {formatRupiah(bill.subtotal)}
                      </div>
                      {bill.lateFee > 0 && (
                        <div className="text-rose-600 font-bold text-[11px]">
                          + Denda Otomatis: {formatRupiah(bill.lateFee)}
                        </div>
                      )}
                    </td>

                    <td className="py-3.5 px-4">
                      <div className="font-black text-slate-900 text-sm">
                        {formatRupiah(bill.totalAmount)}
                      </div>
                      {bill.remainingAmount > 0 && bill.paidAmount > 0 && (
                        <div className="text-[10px] text-blue-600 font-semibold">
                          Terbayar: {formatRupiah(bill.paidAmount)}
                        </div>
                      )}
                    </td>

                    <td className="py-3.5 px-4">
                      <span
                        className={`inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${statusMeta.bg}`}
                      >
                        {statusMeta.label}
                      </span>
                      {pendingProof && (
                        <div className="mt-1.5 flex items-center space-x-1">
                          <button
                            onClick={() => handleVerify(bill.id, true)}
                            className="px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] flex items-center space-x-1"
                            title="Setujui Bukti Transfer"
                          >
                            <CheckCircle2 className="w-3 h-3" />
                            <span>Verifikasi Lunas</span>
                          </button>
                          <button
                            onClick={() => handleVerify(bill.id, false)}
                            className="p-1 rounded bg-rose-100 hover:bg-rose-200 text-rose-700"
                            title="Tolak Bukti"
                          >
                            <XCircle className="w-3.5 h-3.5" />
                          </button>
                        </div>
                      )}
                    </td>

                    <td className="py-3.5 px-4 text-right">
                      <div className="flex items-center justify-end space-x-1.5">
                        <button
                          onClick={() => setSelectedInvoice(bill)}
                          className="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                          title="Lihat & Unduh Invoice"
                        >
                          <Eye className="w-4 h-4" />
                        </button>

                        <button
                          onClick={() => {
                            setCorrectionBillModal(bill);
                            setCorrectionAmount(0);
                            setCorrectionReason("");
                          }}
                          className="p-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 transition"
                          title="Catatan Koreksi Tagihan (Sesuai Aturan Bisnis)"
                        >
                          <Edit3 className="w-4 h-4" />
                        </button>

                        {bill.status !== "LUNAS" && (
                          <a
                            href={waLink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] flex items-center space-x-1 transition"
                            title="Kirim Pengingat WhatsApp"
                          >
                            <MessageCircle className="w-3.5 h-3.5" />
                            <span className="hidden xl:inline">WA</span>
                          </a>
                        )}
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {/* Printable / Downloadable Invoice Modal */}
      {selectedInvoice && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-slate-900 px-6 py-4 text-white flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <FileText className="w-5 h-5 text-orange-400" />
                <h3 className="font-bold text-sm">Invoice Resmi — {selectedInvoice.invoiceNumber}</h3>
              </div>
              <button onClick={() => setSelectedInvoice(null)}>
                <X className="w-5 h-5 text-slate-400 hover:text-white" />
              </button>
            </div>

            <div className="p-6 space-y-5 text-xs">
              <div className="flex justify-between items-start border-b border-slate-100 pb-4">
                <div>
                  <span className="text-lg font-black text-slate-900">
                    eto<span className="text-orange-600">Kos</span> Indonesia
                  </span>
                  <p className="text-slate-500 mt-0.5">
                    {properties.find((p) => p.id === selectedInvoice.propertyId)?.name}
                  </p>
                </div>
                <div className="text-right">
                  <span
                    className={`px-2.5 py-1 rounded-full text-[10px] font-bold border ${
                      getBillStatusMeta(selectedInvoice.status).bg
                    }`}
                  >
                    {getBillStatusMeta(selectedInvoice.status).label}
                  </span>
                  <p className="text-slate-500 mt-1">
                    Jatuh Tempo: {formatTanggal(selectedInvoice.dueDate)}
                  </p>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4 bg-slate-50 p-3.5 rounded-xl">
                <div>
                  <span className="text-slate-400 block">Ditagihkan Kepada:</span>
                  <span className="font-bold text-slate-900 text-sm">
                    {selectedInvoice.tenantName}
                  </span>
                  <span className="block text-slate-600">
                    Kamar #{selectedInvoice.roomNumber}
                  </span>
                </div>
                <div className="text-right">
                  <span className="text-slate-400 block">Periode Sewa:</span>
                  <span className="font-bold text-slate-900 text-sm">
                    {selectedInvoice.periodName}
                  </span>
                </div>
              </div>

              {/* Line Items */}
              <div className="space-y-2">
                <p className="font-bold text-slate-700 uppercase text-[10px] tracking-wider">
                  Rincian Komponen Tagihan:
                </p>
                <div className="border border-slate-200 rounded-xl divide-y divide-slate-100">
                  {selectedInvoice.items.map((item) => (
                    <div key={item.id} className="p-3 flex justify-between">
                      <span className="text-slate-700">{item.name}</span>
                      <span className="font-bold text-slate-900">{formatRupiah(item.amount)}</span>
                    </div>
                  ))}
                  <div className="p-3 bg-orange-50/60 flex justify-between text-sm font-black text-slate-900">
                    <span>Total Tagihan</span>
                    <span className="text-orange-600">
                      {formatRupiah(selectedInvoice.totalAmount)}
                    </span>
                  </div>
                </div>
              </div>

              {/* Payment Proofs if any */}
              {selectedInvoice.paymentProofs.length > 0 && (
                <div className="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                  <p className="font-bold text-slate-800">Riwayat Bukti Pembayaran:</p>
                  {selectedInvoice.paymentProofs.map((pr, idx) => (
                    <div key={idx} className="flex items-center justify-between text-[11px]">
                      <span>
                        {pr.method} — {formatRupiah(pr.amount)} ({pr.uploadedAt})
                      </span>
                      <span className="font-bold text-emerald-700">{pr.status}</span>
                    </div>
                  ))}
                </div>
              )}

              <div className="pt-3 border-t border-slate-100 flex items-center justify-between">
                <button
                  onClick={() => window.print()}
                  className="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold flex items-center space-x-1.5"
                >
                  <Printer className="w-3.5 h-3.5" />
                  <span>Cetak / Simpan PDF</span>
                </button>
                <button
                  onClick={() => setSelectedInvoice(null)}
                  className="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Payment Correction Modal (Enforcing Business Rule: No Delete, Only Correction Note) */}
      {correctionBillModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-amber-600 px-6 py-4 text-white flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <ShieldCheck className="w-5 h-5" />
                <h3 className="font-bold text-sm">Catatan Koreksi Tagihan</h3>
              </div>
              <button onClick={() => setCorrectionBillModal(null)}>
                <X className="w-5 h-5 text-white/80 hover:text-white" />
              </button>
            </div>

            <form onSubmit={handleCorrectionSubmit} className="p-6 space-y-4 text-xs">
              <div className="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px]">
                <strong>Aturan Bisnis etoKos:</strong> Data tagihan & pembayaran tidak boleh dihapus demi integritas akuntansi. Gunakan koreksi nominal disertai alasan yang akan tercatat di Audit Log.
              </div>

              <div>
                <p className="font-bold text-slate-800">
                  {correctionBillModal.invoiceNumber} — {correctionBillModal.tenantName}
                </p>
                <p className="text-slate-500">
                  Total saat ini: {formatRupiah(correctionBillModal.totalAmount)}
                </p>
              </div>

              <div className="space-y-1">
                <label className="font-bold text-slate-700">
                  Nominal Penyesuaian / Koreksi (Rp) *
                </label>
                <input
                  type="number"
                  required
                  placeholder="Contoh: -50000 (potongan) atau 25000 (tambahan)"
                  value={correctionAmount}
                  onChange={(e) => setCorrectionAmount(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg"
                />
                <p className="text-[10px] text-slate-500">
                  Total baru setelah dihitung ulang:{" "}
                  <strong>
                    {formatRupiah(
                      Math.max(
                        0,
                        correctionBillModal.subtotal +
                          correctionBillModal.lateFee +
                          Number(correctionAmount)
                      )
                    )}
                  </strong>{" "}
                  (Tidak boleh negatif)
                </p>
              </div>

              <div className="space-y-1">
                <label className="font-bold text-slate-700">Alasan Koreksi Wajib Diisi *</label>
                <input
                  type="text"
                  required
                  placeholder="Contoh: Penghapusan denda karena konfirmasi sebelumnya"
                  value={correctionReason}
                  onChange={(e) => setCorrectionReason(e.target.value)}
                  className="w-full px-3 py-2 border rounded-lg"
                />
              </div>

              <div className="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                <button
                  type="button"
                  onClick={() => setCorrectionBillModal(null)}
                  className="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold"
                >
                  Simpan Koreksi
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
