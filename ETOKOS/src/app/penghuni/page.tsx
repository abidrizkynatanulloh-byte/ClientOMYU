"use client";

import React, { useState } from "react";
import Link from "next/link";
import { useKosStore } from "@/lib/store";
import {
  formatRupiah,
  formatTanggal,
  getBillStatusMeta,
} from "@/lib/formatters";
import { Bill } from "@/types";
import {
  Receipt,
  Calendar,
  Wallet,
  Upload,
  CheckCircle2,
  Wrench,
  Sparkles,
  X,
  QrCode,
  CreditCard,
  ArrowRight,
  FileText,
} from "lucide-react";

export default function TenantDashboardPage() {
  const {
    activeTenantId,
    tenants,
    properties,
    rooms,
    bills,
    submitPaymentProof,
  } = useKosStore();

  const currentTenant = tenants.find((t) => t.id === activeTenantId) || tenants[0];
  const currentProperty =
    properties.find((p) => p.id === currentTenant.propertyId) || properties[0];
  const currentRoom = rooms.find((r) => r.id === currentTenant.roomId);

  const tenantBills = bills.filter((b) => b.tenantId === currentTenant.id);
  const unpaidBill = tenantBills.find((b) => b.status !== "LUNAS");

  const [payModalBill, setPayModalBill] = useState<Bill | null>(null);
  const [payMethod, setPayMethod] = useState<"TRANSFER_BCA" | "QRIS">("TRANSFER_BCA");
  const [proofSent, setProofSent] = useState(false);

  const handleUploadProof = (e: React.FormEvent) => {
    e.preventDefault();
    if (!payModalBill) return;
    submitPaymentProof(payModalBill.id, {
      imageUrl:
        "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&auto=format&fit=crop&q=80",
      amount: payModalBill.remainingAmount,
      method: payMethod,
    });
    setProofSent(true);
    setTimeout(() => {
      setProofSent(false);
      setPayModalBill(null);
    }, 1500);
  };

  return (
    <div className="space-y-6">
      {/* Quick Wow Flow Banner */}
      <div className="p-4 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div className="flex items-center space-x-3">
          <div className="p-2 bg-white/20 rounded-xl">
            <Sparkles className="w-5 h-5 text-yellow-200" />
          </div>
          <div>
            <h2 className="text-sm font-bold">Ada Masalah Fasilitas Kamar (Misal: AC Rusak)?</h2>
            <p className="text-xs text-amber-100">
              Laporkan keluhan beserta foto, pantau teknisi yang ditugaskan secara transparan.
            </p>
          </div>
        </div>
        <Link
          href="/penghuni/keluhan"
          className="px-4 py-2 rounded-xl bg-white text-orange-700 font-bold text-xs hover:bg-orange-50 transition flex items-center space-x-1.5 self-start sm:self-center whitespace-nowrap"
        >
          <Wrench className="w-3.5 h-3.5" />
          <span>Lapor Keluhan Sekarang</span>
          <ArrowRight className="w-3.5 h-3.5" />
        </Link>
      </div>

      {/* Contract & Room Info Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-2">
          <span className="text-xs font-semibold text-slate-500">Kamar & Properti Saya</span>
          <h3 className="text-lg font-black text-slate-900">
            Kamar #{currentTenant.roomNumber} ({currentRoom?.type || "Deluxe"})
          </h3>
          <p className="text-xs text-slate-500">{currentProperty.name}</p>
        </div>

        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-2">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Masa Kontrak Aktif</span>
            <Calendar className="w-4 h-4 text-blue-500" />
          </div>
          <p className="text-sm font-bold text-slate-900">
            {formatTanggal(currentTenant.contractStart)} –{" "}
            {formatTanggal(currentTenant.contractEnd)}
          </p>
          <p className="text-xs text-emerald-600 font-semibold">
            Sewa Pokok: {formatRupiah(currentTenant.monthlyRent)} / bln
          </p>
        </div>

        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-2">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Saldo Deposit Jaminan</span>
            <Wallet className="w-4 h-4 text-emerald-500" />
          </div>
          <p className="text-lg font-black text-emerald-600">
            {formatRupiah(currentTenant.depositAmount)}
          </p>
          <p className="text-[11px] text-slate-500">
            Status: <strong>{currentTenant.depositStatus}</strong> (Utuh & Aman)
          </p>
        </div>
      </div>

      {/* Active Unpaid Bill Highlight */}
      {unpaidBill && (
        <div className="bg-white rounded-2xl border-2 border-orange-400 p-6 shadow-sm space-y-4">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
              <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800">
                Tagihan Bulan Berjalan ({unpaidBill.periodName})
              </span>
              <h2 className="text-xl font-black text-slate-900 mt-1">
                {unpaidBill.invoiceNumber} — Total {formatRupiah(unpaidBill.remainingAmount)}
              </h2>
              <p className="text-xs text-slate-500">
                Jatuh Tempo: <strong>{formatTanggal(unpaidBill.dueDate)}</strong>
              </p>
            </div>

            <div>
              {unpaidBill.paymentProofs.some((p) => p.status === "PENDING") ? (
                <span className="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-amber-100 text-amber-900 font-bold text-xs border border-amber-300">
                  <span>⏳ Bukti Bayar Sedang Diverifikasi Owner</span>
                </span>
              ) : (
                <button
                  onClick={() => setPayModalBill(unpaidBill)}
                  className="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs transition shadow-sm flex items-center space-x-2"
                >
                  <Upload className="w-4 h-4" />
                  <span>Bayar & Unggah Bukti Transfer</span>
                </button>
              )}
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            {unpaidBill.items.map((it) => (
              <div
                key={it.id}
                className="p-3 rounded-xl bg-slate-50 border border-slate-200/70 flex justify-between items-center"
              >
                <span className="text-slate-600">{it.name}</span>
                <span className="font-bold text-slate-900">{formatRupiah(it.amount)}</span>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Payment History Table */}
      <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="text-base font-bold text-slate-900">
              Tagihan Saya & Riwayat Pembayaran (6 Bulan)
            </h2>
            <p className="text-xs text-slate-500">
              Transparansi seluruh invoice sewa kamar dan bukti pembayaran Anda
            </p>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse text-xs">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <th className="py-3 px-4">No. Invoice</th>
                <th className="py-3 px-4">Periode</th>
                <th className="py-3 px-4">Jatuh Tempo</th>
                <th className="py-3 px-4">Total Tagihan</th>
                <th className="py-3 px-4">Status</th>
                <th className="py-3 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {tenantBills.map((b) => {
                const meta = getBillStatusMeta(b.status);
                const hasPending = b.paymentProofs.some((p) => p.status === "PENDING");
                return (
                  <tr key={b.id} className="hover:bg-slate-50">
                    <td className="py-3 px-4 font-bold text-slate-900">{b.invoiceNumber}</td>
                    <td className="py-3 px-4 font-semibold text-slate-700">{b.periodName}</td>
                    <td className="py-3 px-4 text-slate-500">{formatTanggal(b.dueDate)}</td>
                    <td className="py-3 px-4 font-black text-slate-900">
                      {formatRupiah(b.totalAmount)}
                    </td>
                    <td className="py-3 px-4">
                      <span
                        className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${meta.bg}`}
                      >
                        {meta.label}
                      </span>
                      {hasPending && (
                        <span className="ml-1.5 text-[10px] text-amber-600 font-semibold">
                          (Menunggu Verifikasi)
                        </span>
                      )}
                    </td>
                    <td className="py-3 px-4 text-right">
                      {b.status !== "LUNAS" && !hasPending ? (
                        <button
                          onClick={() => setPayModalBill(b)}
                          className="px-3 py-1.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold text-[11px]"
                        >
                          Unggah Bukti
                        </button>
                      ) : (
                        <span className="text-[11px] text-emerald-600 font-semibold">
                          ✓ Terbayar {b.paidAt ? `(${formatTanggal(b.paidAt)})` : ""}
                        </span>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {/* Upload Payment Proof Modal */}
      {payModalBill && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-orange-600 px-6 py-4 text-white flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <Upload className="w-5 h-5" />
                <h3 className="font-bold text-sm">Pembayaran & Unggah Bukti Transfer</h3>
              </div>
              <button onClick={() => setPayModalBill(null)}>
                <X className="w-5 h-5 text-white/80 hover:text-white" />
              </button>
            </div>

            {proofSent ? (
              <div className="p-8 text-center space-y-3">
                <CheckCircle2 className="w-12 h-12 text-emerald-600 mx-auto" />
                <h4 className="text-base font-bold text-slate-900">
                  Bukti Pembayaran Berhasil Diunggah!
                </h4>
                <p className="text-xs text-slate-600">
                  Owner akan memverifikasi bukti transfer Anda. Anda dapat beralih ke Portal Owner untuk menyetujui pembayaran ini.
                </p>
              </div>
            ) : (
              <form onSubmit={handleUploadProof} className="p-6 space-y-4 text-xs">
                <div className="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                  <div>
                    <p className="font-bold text-slate-900">{payModalBill.invoiceNumber}</p>
                    <p className="text-slate-500">Periode {payModalBill.periodName}</p>
                  </div>
                  <span className="text-base font-black text-orange-600">
                    {formatRupiah(payModalBill.remainingAmount)}
                  </span>
                </div>

                {/* Method Selection */}
                <div className="space-y-1.5">
                  <label className="font-bold text-slate-700 block">Pilih Metode Pembayaran:</label>
                  <div className="grid grid-cols-2 gap-2">
                    <button
                      type="button"
                      onClick={() => setPayMethod("TRANSFER_BCA")}
                      className={`p-3 rounded-xl border text-left transition flex items-center space-x-2 ${
                        payMethod === "TRANSFER_BCA"
                          ? "border-orange-600 bg-orange-50/50 font-bold"
                          : "border-slate-200"
                      }`}
                    >
                      <CreditCard className="w-4 h-4 text-orange-600" />
                      <span>Transfer BCA</span>
                    </button>
                    <button
                      type="button"
                      onClick={() => setPayMethod("QRIS")}
                      className={`p-3 rounded-xl border text-left transition flex items-center space-x-2 ${
                        payMethod === "QRIS"
                          ? "border-orange-600 bg-orange-50/50 font-bold"
                          : "border-slate-200"
                      }`}
                    >
                      <QrCode className="w-4 h-4 text-orange-600" />
                      <span>QRIS Instan</span>
                    </button>
                  </div>
                </div>

                <div className="p-3 rounded-xl bg-blue-50/60 border border-blue-200 text-blue-950 space-y-1">
                  <p className="font-bold">{currentProperty.bankAccount.bankName}</p>
                  <p className="text-sm font-black">{currentProperty.bankAccount.accountNumber}</p>
                  <p className="text-[11px]">a.n {currentProperty.bankAccount.accountHolder}</p>
                </div>

                <div className="space-y-1">
                  <label className="font-bold text-slate-700">
                    Lampiran Foto Struk / Screenshot m-Banking *
                  </label>
                  <div className="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50">
                    <p className="text-slate-600 font-medium">
                      📄 bukti-transfer-bca-juni2026.jpg (Siap diunggah)
                    </p>
                  </div>
                </div>

                <div className="pt-2 flex justify-end space-x-2">
                  <button
                    type="button"
                    onClick={() => setPayModalBill(null)}
                    className="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold"
                  >
                    Batal
                  </button>
                  <button
                    type="submit"
                    className="px-5 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold"
                  >
                    Kirim Bukti Pembayaran
                  </button>
                </div>
              </form>
            )}
          </div>
        </div>
      )}
    </div>
  );
}
