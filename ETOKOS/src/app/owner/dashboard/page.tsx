"use client";

import React from "react";
import Link from "next/link";
import { useKosStore } from "@/lib/store";
import {
  formatRupiah,
  formatTanggal,
  getBillStatusMeta,
  getTicketStatusMeta,
  getPriorityMeta,
} from "@/lib/formatters";
import {
  BedDouble,
  TrendingUp,
  AlertCircle,
  Wrench,
  CalendarClock,
  ArrowUpRight,
  Building2,
  CheckCircle2,
  Plus,
  Receipt,
  Sparkles,
  Users,
} from "lucide-react";
import {
  ResponsiveContainer,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
} from "recharts";

export default function OwnerDashboardPage() {
  const {
    selectedPropertyId,
    properties,
    rooms,
    tenants,
    bills,
    tickets,
    expenses,
  } = useKosStore();

  // Filter by selected property
  const filteredRooms = rooms.filter(
    (r) => selectedPropertyId === "all" || r.propertyId === selectedPropertyId
  );
  const filteredTenants = tenants.filter(
    (t) => selectedPropertyId === "all" || t.propertyId === selectedPropertyId
  );
  const filteredBills = bills.filter(
    (b) => selectedPropertyId === "all" || b.propertyId === selectedPropertyId
  );
  const filteredTickets = tickets.filter(
    (t) => selectedPropertyId === "all" || t.propertyId === selectedPropertyId
  );
  const filteredExpenses = expenses.filter(
    (e) => selectedPropertyId === "all" || e.propertyId === selectedPropertyId
  );

  // 1. Occupancy Calculation
  const totalRooms = filteredRooms.length;
  const occupiedRooms = filteredRooms.filter((r) => r.status === "TERISI").length;
  const availableRooms = filteredRooms.filter((r) => r.status === "TERSEDIA").length;
  const bookedRooms = filteredRooms.filter((r) => r.status === "DIPESAN").length;
  const maintenanceRooms = filteredRooms.filter((r) => r.status === "PERBAIKAN").length;
  const occupancyRate = totalRooms > 0 ? Math.round((occupiedRooms / totalRooms) * 100) : 0;

  // 2. Current Month (2026-06) Income & Expenses
  const currentMonth = "2026-06";
  const currentMonthBills = filteredBills.filter((b) => b.billingMonth === currentMonth);
  const monthlyIncome = currentMonthBills.reduce((acc, b) => acc + b.paidAmount, 0);
  const potentialMonthlyIncome = currentMonthBills.reduce((acc, b) => acc + b.totalAmount, 0);

  const currentMonthExpenses = filteredExpenses
    .filter((e) => e.date.startsWith(currentMonth))
    .reduce((acc, e) => acc + e.amount, 0);

  const netOperatingIncome = monthlyIncome - currentMonthExpenses;

  // 3. Overdue / Unpaid Bills
  const unpaidBills = filteredBills.filter(
    (b) => b.status === "TELAT" || b.status === "BELUM_BAYAR" || b.status === "SEBAGIAN"
  );
  const totalOverdueAmount = unpaidBills.reduce((acc, b) => acc + b.remainingAmount, 0);
  const lateBillsCount = filteredBills.filter((b) => b.status === "TELAT").length;

  // 4. Open Tickets
  const openTickets = filteredTickets.filter(
    (t) => t.status === "BARU" || t.status === "DIPROSES"
  );

  // 5. Expiring Contracts (ending in June or July 2026)
  const expiringContracts = filteredTenants.filter(
    (t) => t.contractEnd <= "2026-07-31"
  );

  // 6. 6-Month Revenue & Expense Chart Data (Jan - Jun 2026)
  // Note: To make historical chart realistic across all 18 tenants, we combine actual bill records with baseline historical occupancy revenue
  const monthsData = [
    { key: "2026-01", label: "Jan 26", baseRev: 29500000, baseExp: 4800000 },
    { key: "2026-02", label: "Feb 26", baseRev: 31200000, baseExp: 5100000 },
    { key: "2026-03", label: "Mar 26", baseRev: 32800000, baseExp: 4950000 },
    { key: "2026-04", label: "Apr 26", baseRev: 33400000, baseExp: 5400000 },
    { key: "2026-05", label: "Mei 26", baseRev: 34900000, baseExp: 5865000 },
    { key: "2026-06", label: "Jun 26", baseRev: 26500000, baseExp: 0 },
  ].map((m) => {
    const scale = selectedPropertyId === "all" ? 1 : selectedPropertyId === "prop-1" ? 0.65 : 0.35;
    if (m.key === "2026-06") {
      const actualPaid = currentMonthBills.reduce((s, b) => s + b.paidAmount, 0);
      const actualExp = currentMonthExpenses;
      return {
        bulan: m.label,
        Pendapatan: actualPaid + Math.round(18000000 * scale),
        Pengeluaran: actualExp,
        LabaBersih: actualPaid + Math.round(18000000 * scale) - actualExp,
      };
    }
    const rev = Math.round(m.baseRev * scale);
    const exp = Math.round(m.baseExp * scale);
    return {
      bulan: m.label,
      Pendapatan: rev,
      Pengeluaran: exp,
      LabaBersih: rev - exp,
    };
  });

  const latestData = monthsData[monthsData.length - 1];

  return (
    <div className="space-y-6">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
            Dashboard Operasional & Keuangan
          </h1>
          <p className="text-xs text-slate-500 mt-0.5">
            Pantau tingkat hunian, arus kas, tagihan jatuh tempo, dan tiket keluhan secara real-time.
          </p>
        </div>

        <div className="flex flex-wrap items-center gap-2">
          <Link
            href="/owner/tagihan"
            className="px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition flex items-center space-x-1.5 shadow-2xs"
          >
            <Receipt className="w-3.5 h-3.5 text-orange-500" />
            <span>Kelola Tagihan</span>
          </Link>
          <Link
            href="/owner/keluhan"
            className="px-3.5 py-2 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold transition flex items-center space-x-1.5 shadow-sm"
          >
            <Wrench className="w-3.5 h-3.5" />
            <span>Papan Keluhan ({openTickets.length})</span>
          </Link>
        </div>
      </div>

      {/* KPI Cards Grid (5 Key Metrics) */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {/* 1. Okupansi */}
        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs flex flex-col justify-between">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Tingkat Okupansi</span>
            <div className="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
              <BedDouble className="w-4 h-4" />
            </div>
          </div>
          <div className="my-3">
            <div className="flex items-baseline space-x-2">
              <span className="text-2xl font-black text-slate-900">{occupancyRate}%</span>
              <span className="text-xs font-semibold text-emerald-600">
                ({occupiedRooms}/{totalRooms} Kamar)
              </span>
            </div>
            <div className="w-full h-2 bg-slate-100 rounded-full mt-2 overflow-hidden">
              <div
                className="h-full bg-emerald-500 rounded-full transition-all duration-500"
                style={{ width: `${occupancyRate}%` }}
              />
            </div>
          </div>
          <p className="text-[11px] text-slate-500">
            {availableRooms} kosong • {bookedRooms} dipesan • {maintenanceRooms} perbaikan
          </p>
        </div>

        {/* 2. Pendapatan Bulan Ini */}
        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs flex flex-col justify-between">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Pendapatan (Juni)</span>
            <div className="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
              <TrendingUp className="w-4 h-4" />
            </div>
          </div>
          <div className="my-3">
            <span className="text-xl font-black text-slate-900 block">
              {formatRupiah(latestData.Pendapatan)}
            </span>
            <span className="text-[11px] text-emerald-600 font-semibold flex items-center mt-1">
              <ArrowUpRight className="w-3 h-3 mr-0.5" />
              Laba Bersih: {formatRupiah(latestData.LabaBersih)}
            </span>
          </div>
          <Link
            href="/owner/akuntansi"
            className="text-[11px] text-orange-600 font-semibold hover:underline"
          >
            Lihat Laba Rugi →
          </Link>
        </div>

        {/* 3. Tagihan Menunggak */}
        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs flex flex-col justify-between">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Tagihan Menunggak</span>
            <div className="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
              <AlertCircle className="w-4 h-4" />
            </div>
          </div>
          <div className="my-3">
            <span className="text-xl font-black text-rose-600 block">
              {formatRupiah(totalOverdueAmount)}
            </span>
            <span className="text-[11px] text-slate-500 mt-1 block">
              {unpaidBills.length} invoice belum lunas ({lateBillsCount} telat)
            </span>
          </div>
          <Link
            href="/owner/tagihan"
            className="text-[11px] text-rose-600 font-semibold hover:underline"
          >
            Kirim Pengingat WA →
          </Link>
        </div>

        {/* 4. Tiket Keluhan Terbuka */}
        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs flex flex-col justify-between">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Tiket Terbuka</span>
            <div className="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
              <Wrench className="w-4 h-4" />
            </div>
          </div>
          <div className="my-3">
            <span className="text-2xl font-black text-slate-900">{openTickets.length}</span>
            <span className="text-xs text-slate-500 ml-1.5">tiket aktif</span>
            <p className="text-[11px] text-amber-700 font-medium mt-1">
              {filteredTickets.filter((t) => t.status === "BARU").length} baru menunggu teknisi
            </p>
          </div>
          <Link
            href="/owner/keluhan"
            className="text-[11px] text-amber-600 font-semibold hover:underline"
          >
            Tugaskan Teknisi →
          </Link>
        </div>

        {/* 5. Kontrak Segera Habis */}
        <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs flex flex-col justify-between">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500">Kontrak Segera Habis</span>
            <div className="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
              <CalendarClock className="w-4 h-4" />
            </div>
          </div>
          <div className="my-3">
            <span className="text-2xl font-black text-slate-900">
              {expiringContracts.length}
            </span>
            <span className="text-xs text-slate-500 ml-1.5">penghuni</span>
            <p className="text-[11px] text-slate-500 mt-1">
              Berakhir dalam &lt; 45 hari ke depan
            </p>
          </div>
          <Link
            href="/owner/kamar"
            className="text-[11px] text-blue-600 font-semibold hover:underline"
          >
            Cek Masa Sewa →
          </Link>
        </div>
      </div>

      {/* Charts & Property Performance Row */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* 6-Month Revenue vs Expense Chart */}
        <div className="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
              <h2 className="text-base font-bold text-slate-900">
                Grafik Pendapatan & Pengeluaran (6 Bulan Terakhir)
              </h2>
              <p className="text-xs text-slate-500">
                Perbandingan arus kas masuk (sewa + utilitas) terhadap beban operasional & perbaikan
              </p>
            </div>
            <span className="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
              Januari – Juni 2026
            </span>
          </div>

          <div className="h-72 w-full pt-2">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={monthsData} margin={{ top: 10, right: 10, left: 10, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                <XAxis dataKey="bulan" tick={{ fontSize: 12, fill: "#64748b" }} />
                <YAxis
                  tickFormatter={(val) => `${(val / 1000000).toFixed(0)}jt`}
                  tick={{ fontSize: 12, fill: "#64748b" }}
                />
                <Tooltip
                  formatter={(value: any) => formatRupiah(Number(value))}
                  contentStyle={{
                    borderRadius: "12px",
                    border: "1px solid #e2e8f0",
                    fontSize: "12px",
                  }}
                />
                <Legend wrapperStyle={{ fontSize: "12px", paddingTop: "8px" }} />
                <Bar
                  dataKey="Pendapatan"
                  name="Pendapatan Kotor"
                  fill="#ea580c"
                  radius={[6, 6, 0, 0]}
                />
                <Bar
                  dataKey="LabaBersih"
                  name="Laba Operasional"
                  fill="#10b981"
                  radius={[6, 6, 0, 0]}
                />
                <Bar
                  dataKey="Pengeluaran"
                  name="Beban Pengeluaran"
                  fill="#f43f5e"
                  radius={[6, 6, 0, 0]}
                />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        {/* Multi-Property Performance Comparison */}
        <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs flex flex-col justify-between space-y-4">
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-base font-bold text-slate-900">Performa Properti</h2>
                <p className="text-xs text-slate-500">Perbandingan cabang kos</p>
              </div>
              <Building2 className="w-5 h-5 text-orange-500" />
            </div>

            <div className="space-y-4">
              {properties.map((prop) => {
                const pRooms = rooms.filter((r) => r.propertyId === prop.id);
                const pOcc = pRooms.filter((r) => r.status === "TERISI").length;
                const pRate = Math.round((pOcc / pRooms.length) * 100);
                const pExp = expenses
                  .filter((e) => e.propertyId === prop.id && e.date.startsWith("2026-06"))
                  .reduce((s, e) => s + e.amount, 0);

                return (
                  <div
                    key={prop.id}
                    className="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5"
                  >
                    <div className="flex items-center justify-between">
                      <div>
                        <h3 className="text-xs font-bold text-slate-900">{prop.name}</h3>
                        <p className="text-[11px] text-slate-500">
                          {prop.city} • Kos {prop.type}
                        </p>
                      </div>
                      <span className="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                        {pRate}% Terisi
                      </span>
                    </div>

                    <div className="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                      <div
                        className="h-full bg-orange-500 rounded-full"
                        style={{ width: `${pRate}%` }}
                      />
                    </div>

                    <div className="grid grid-cols-2 gap-2 pt-1 text-[11px]">
                      <div>
                        <span className="text-slate-400 block">Kamar Terisi</span>
                        <span className="font-bold text-slate-800">
                          {pOcc} dari {pRooms.length} Kamar
                        </span>
                      </div>
                      <div>
                        <span className="text-slate-400 block">Beban Bulan Ini</span>
                        <span className="font-bold text-rose-600">{formatRupiah(pExp)}</span>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>

          <div className="p-3.5 rounded-xl bg-orange-50 border border-orange-200/80 text-xs text-orange-950 flex items-start space-x-2.5">
            <Sparkles className="w-4 h-4 text-orange-600 flex-shrink-0 mt-0.5" />
            <div>
              <p className="font-bold">Tips Optimalisasi Okupansi</p>
              <p className="text-[11px] text-orange-800 mt-0.5">
                Terdapat {availableRooms} kamar kosong siap huni. Bagikan link listing publik ke WhatsApp untuk mempercepat pengisian.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Row: Open Tickets, Unpaid Bills & Expiring Contracts */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Tiket Keluhan Terbaru */}
        <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
          <div className="flex items-center justify-between">
            <h2 className="text-sm font-bold text-slate-900">Tiket Keluhan Aktif</h2>
            <Link
              href="/owner/keluhan"
              className="text-xs font-semibold text-orange-600 hover:underline"
            >
              Semua Tiket →
            </Link>
          </div>

          <div className="space-y-3">
            {openTickets.slice(0, 4).map((tkt) => {
              const sMeta = getTicketStatusMeta(tkt.status);
              const pMeta = getPriorityMeta(tkt.priority);
              return (
                <div
                  key={tkt.id}
                  className="p-3 rounded-xl border border-slate-100 hover:border-slate-200 bg-slate-50/50 space-y-1.5"
                >
                  <div className="flex items-center justify-between">
                    <span className="text-[11px] font-bold text-slate-800">
                      Kamar #{tkt.roomNumber} • {tkt.tenantName}
                    </span>
                    <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${pMeta.bg}`}>
                      {pMeta.label}
                    </span>
                  </div>
                  <p className="text-xs font-semibold text-slate-900">{tkt.title}</p>
                  <div className="flex items-center justify-between pt-1">
                    <span className="text-[10px] text-slate-500">{tkt.category}</span>
                    <span
                      className={`px-2 py-0.5 rounded-full text-[10px] font-bold border ${sMeta.bg}`}
                    >
                      {sMeta.label}
                    </span>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Tagihan Belum Lunas / Menunggak */}
        <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
          <div className="flex items-center justify-between">
            <h2 className="text-sm font-bold text-slate-900">Tagihan Perlu Tindakan</h2>
            <Link
              href="/owner/tagihan"
              className="text-xs font-semibold text-orange-600 hover:underline"
            >
              Kelola Tagihan →
            </Link>
          </div>

          <div className="space-y-3">
            {unpaidBills.slice(0, 4).map((bill) => {
              const bMeta = getBillStatusMeta(bill.status);
              const hasPendingProof = bill.paymentProofs.some((p) => p.status === "PENDING");
              return (
                <div
                  key={bill.id}
                  className="p-3 rounded-xl border border-slate-100 bg-slate-50/50 space-y-1.5"
                >
                  <div className="flex items-center justify-between">
                    <span className="text-xs font-bold text-slate-900">
                      {bill.tenantName} (Kmr #{bill.roomNumber})
                    </span>
                    <span
                      className={`px-2 py-0.5 rounded-full text-[10px] font-bold border ${bMeta.bg}`}
                    >
                      {bMeta.label}
                    </span>
                  </div>
                  <div className="flex items-center justify-between text-xs">
                    <span className="text-slate-500">Sisa Tagihan:</span>
                    <span className="font-extrabold text-rose-600">
                      {formatRupiah(bill.remainingAmount)}
                    </span>
                  </div>
                  {hasPendingProof && (
                    <div className="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded border border-amber-200">
                      ⏳ Bukti transfer menunggu verifikasi Anda!
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>

        {/* Kontrak Segera Berakhir */}
        <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
          <div className="flex items-center justify-between">
            <h2 className="text-sm font-bold text-slate-900">Kontrak Segera Berakhir</h2>
            <Link
              href="/owner/kamar"
              className="text-xs font-semibold text-orange-600 hover:underline"
            >
              Lihat Penghuni →
            </Link>
          </div>

          <div className="space-y-3">
            {expiringContracts.map((tenant) => (
              <div
                key={tenant.id}
                className="p-3 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between"
              >
                <div className="flex items-center space-x-2.5">
                  <img
                    src={tenant.avatarUrl}
                    alt={tenant.name}
                    className="w-9 h-9 rounded-full object-cover"
                  />
                  <div>
                    <p className="text-xs font-bold text-slate-900">{tenant.name}</p>
                    <p className="text-[11px] text-slate-500">
                      Kamar #{tenant.roomNumber} • Berakhir: {formatTanggal(tenant.contractEnd)}
                    </p>
                  </div>
                </div>
                <span className="px-2 py-1 rounded-lg bg-amber-100 text-amber-800 font-bold text-[10px]">
                  Perpanjang
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
