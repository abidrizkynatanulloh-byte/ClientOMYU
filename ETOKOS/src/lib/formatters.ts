import { type ClassValue, clsx } from "clsx";
import { twMerge } from "tailwind-merge";
import { RoomStatus, BillStatus, TicketStatus, TicketPriority } from "@/types";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function formatRupiah(amount: number): string {
  if (isNaN(amount)) amount = 0;
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount);
}

export function formatTanggal(dateStr: string): string {
  if (!dateStr) return "-";
  try {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat("id-ID", {
      day: "numeric",
      month: "long",
      year: "numeric",
    }).format(d);
  } catch {
    return dateStr;
  }
}

export function formatTanggalWaktu(dateStr: string): string {
  if (!dateStr) return "-";
  try {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat("id-ID", {
      day: "numeric",
      month: "short",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    }).format(d);
  } catch {
    return dateStr;
  }
}

export function getRoomStatusMeta(status: RoomStatus) {
  switch (status) {
    case "TERSEDIA":
      return {
        label: "Kosong (Tersedia)",
        bg: "bg-emerald-50 text-emerald-700 border-emerald-200",
        badge: "bg-emerald-500",
        cardBorder: "border-emerald-300 hover:border-emerald-500",
      };
    case "TERISI":
      return {
        label: "Terisi",
        bg: "bg-blue-50 text-blue-700 border-blue-200",
        badge: "bg-blue-500",
        cardBorder: "border-blue-300 hover:border-blue-500",
      };
    case "DIPESAN":
      return {
        label: "Dipesan (Booking)",
        bg: "bg-amber-50 text-amber-700 border-amber-200",
        badge: "bg-amber-500",
        cardBorder: "border-amber-300 hover:border-amber-500",
      };
    case "PERBAIKAN":
      return {
        label: "Dalam Perbaikan",
        bg: "bg-rose-50 text-rose-700 border-rose-200",
        badge: "bg-rose-500",
        cardBorder: "border-rose-300 hover:border-rose-500",
      };
  }
}

export function getBillStatusMeta(status: BillStatus) {
  switch (status) {
    case "LUNAS":
      return {
        label: "Lunas",
        bg: "bg-emerald-100 text-emerald-800 border-emerald-300",
      };
    case "BELUM_BAYAR":
      return {
        label: "Belum Bayar",
        bg: "bg-slate-100 text-slate-800 border-slate-300",
      };
    case "SEBAGIAN":
      return {
        label: "Sebagian",
        bg: "bg-blue-100 text-blue-800 border-blue-300",
      };
    case "TELAT":
      return {
        label: "Menunggak (Telat)",
        bg: "bg-rose-100 text-rose-800 border-rose-300",
      };
  }
}

export function getTicketStatusMeta(status: TicketStatus) {
  switch (status) {
    case "BARU":
      return {
        label: "Tiket Baru",
        bg: "bg-amber-100 text-amber-800 border-amber-300",
        dot: "bg-amber-500",
      };
    case "DIPROSES":
      return {
        label: "Sedang Diproses",
        bg: "bg-blue-100 text-blue-800 border-blue-300",
        dot: "bg-blue-500",
      };
    case "SELESAI":
      return {
        label: "Selesai",
        bg: "bg-emerald-100 text-emerald-800 border-emerald-300",
        dot: "bg-emerald-500",
      };
  }
}

export function getPriorityMeta(priority: TicketPriority) {
  switch (priority) {
    case "DARURAT":
      return { label: "Darurat", bg: "bg-rose-600 text-white" };
    case "TINGGI":
      return { label: "Tinggi", bg: "bg-orange-500 text-white" };
    case "SEDANG":
      return { label: "Sedang", bg: "bg-amber-500 text-white" };
    case "RENDAH":
      return { label: "Rendah", bg: "bg-slate-400 text-white" };
  }
}

export function buildWhatsAppLink(phone: string, text: string): string {
  let cleanPhone = phone.replace(/[^0-9]/g, "");
  if (cleanPhone.startsWith("0")) {
    cleanPhone = "62" + cleanPhone.slice(1);
  }
  return `https://wa.me/${cleanPhone}?text=${encodeURIComponent(text)}`;
}
