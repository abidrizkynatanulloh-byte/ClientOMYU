"use client";

import React, { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import {
  LayoutDashboard,
  BedDouble,
  Receipt,
  Wrench,
  BadgePercent,
  Settings,
  History,
  Building2,
  ChevronRight,
  Menu,
  X,
  Sparkles,
} from "lucide-react";
import { useKosStore } from "@/lib/store";

export function OwnerSidebar() {
  const pathname = usePathname();
  const { tickets, bills } = useKosStore();
  const [mobileOpen, setMobileOpen] = useState(false);

  // Counters for badge
  const pendingTickets = tickets.filter((t) => t.status === "BARU").length;
  const pendingProofs = bills.flatMap((b) => b.paymentProofs).filter((p) => p.status === "PENDING").length;

  const navItems = [
    {
      href: "/owner/dashboard",
      label: "Dashboard",
      icon: LayoutDashboard,
    },
    {
      href: "/owner/kamar",
      label: "Kamar & Penghuni",
      icon: BedDouble,
    },
    {
      href: "/owner/tagihan",
      label: "Tagihan & Pembayaran",
      icon: Receipt,
      badge: pendingProofs > 0 ? `${pendingProofs} verif` : undefined,
      badgeColor: "bg-amber-500",
    },
    {
      href: "/owner/keluhan",
      label: "Tiket Keluhan",
      icon: Wrench,
      badge: pendingTickets > 0 ? `${pendingTickets} baru` : undefined,
      badgeColor: "bg-rose-500",
    },
    {
      href: "/owner/akuntansi",
      label: "Akuntansi & Laba Rugi",
      icon: BadgePercent,
    },
    {
      href: "/owner/pengaturan",
      label: "Pengaturan Kos",
      icon: Settings,
    },
    {
      href: "/owner/aktivitas",
      label: "Log Aktivitas (Audit)",
      icon: History,
    },
  ];

  return (
    <>
      {/* Mobile Top Header for Owner */}
      <div className="lg:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-slate-200">
        <div className="flex items-center space-x-2">
          <div className="w-8 h-8 rounded-lg bg-orange-600 flex items-center justify-center text-white font-bold text-sm">
            eK
          </div>
          <span className="font-bold text-slate-800 text-sm">Portal Owner</span>
        </div>
        <button
          onClick={() => setMobileOpen(!mobileOpen)}
          className="p-2 text-slate-600 hover:bg-slate-100 rounded-lg"
        >
          {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
        </button>
      </div>

      {/* Sidebar Desktop & Mobile */}
      <aside
        className={`fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-200 flex flex-col border-r border-slate-800 transform transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 ${
          mobileOpen ? "translate-x-0 top-[41px]" : "-translate-x-full lg:translate-x-0"
        }`}
      >
        {/* Sidebar Brand */}
        <div className="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
          <Link href="/owner/dashboard" className="flex items-center space-x-2.5">
            <div className="w-9 h-9 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white font-black text-lg shadow">
              eK
            </div>
            <div>
              <span className="text-lg font-bold text-white tracking-tight">
                eto<span className="text-orange-500">Kos</span>
              </span>
              <p className="text-[10px] text-orange-400 font-medium">Owner Management Suite</p>
            </div>
          </Link>
          <button
            onClick={() => setMobileOpen(false)}
            className="lg:hidden text-slate-400 hover:text-white"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Manager Profile summary */}
        <div className="px-5 py-4 bg-slate-800/40 border-b border-slate-800 flex items-center space-x-3">
          <div className="w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-center font-bold text-white text-sm shadow-inner">
            HW
          </div>
          <div className="overflow-hidden">
            <p className="text-xs font-semibold text-white truncate">Hendra Wijaya</p>
            <p className="text-[11px] text-slate-400">Pemilik & Pengelola</p>
          </div>
        </div>

        {/* Navigation items */}
        <nav className="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
          {navItems.map((item) => {
            const isActive = pathname === item.href;
            const Icon = item.icon;
            return (
              <Link
                key={item.href}
                href={item.href}
                onClick={() => setMobileOpen(false)}
                className={`flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium transition group ${
                  isActive
                    ? "bg-orange-600 text-white shadow-md font-semibold"
                    : "text-slate-300 hover:bg-slate-800/70 hover:text-white"
                }`}
              >
                <div className="flex items-center space-x-3">
                  <Icon
                    className={`w-4 h-4 transition ${
                      isActive ? "text-white" : "text-slate-400 group-hover:text-orange-400"
                    }`}
                  />
                  <span>{item.label}</span>
                </div>
                {item.badge && (
                  <span
                    className={`px-2 py-0.5 text-[10px] rounded-full text-white font-bold ${item.badgeColor}`}
                  >
                    {item.badge}
                  </span>
                )}
              </Link>
            );
          })}
        </nav>

        {/* Bottom Banner */}
        <div className="p-4 border-t border-slate-800">
          <div className="p-3 rounded-xl bg-gradient-to-br from-slate-800 to-slate-800/80 border border-slate-700/60">
            <div className="flex items-center space-x-2 text-xs font-semibold text-orange-400 mb-1">
              <Sparkles className="w-3.5 h-3.5" />
              <span>Multi-Tenant Properti</span>
            </div>
            <p className="text-[11px] text-slate-400 leading-snug">
              Mengelola 2 properti (24 kamar). Data tersimpan di browser Anda.
            </p>
          </div>
        </div>
      </aside>

      {/* Backdrop for mobile */}
      {mobileOpen && (
        <div
          onClick={() => setMobileOpen(false)}
          className="fixed inset-0 z-20 bg-black/50 lg:hidden"
        />
      )}
    </>
  );
}
