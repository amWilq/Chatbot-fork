import { Injectable } from '@angular/core';
import { BehaviorSubject, Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TimerService {
  private startTime: number | null = null;
  private totalSpentTime: number = 0;
  private countdownValue: number = 0;
  private intervalId: any;
  private timeLeft = new BehaviorSubject<number>(0);
  private initialCountdownValue: number = 0;

  private timeUpSource = new Subject<void>();
  timeUp$ = this.timeUpSource.asObservable();

  startCountdown(minutes: number) {
    this.startTime = Date.now();
    this.countdownValue = minutes * 60 * 1000; // convert minutes to milliseconds
    this.initialCountdownValue = this.countdownValue; // store the initial countdown value
    this.timeLeft.next(this.countdownValue);
    this.intervalId = setInterval(() => {
      this.countdownValue -= 1000;
      this.timeLeft.next(this.countdownValue);
      if (this.countdownValue <= 0) {
        clearInterval(this.intervalId);
        this.timeUpSource.next();
      }
    }, 1000);
  }

  pauseCountdown() {
    if (this.intervalId) {
      clearInterval(this.intervalId);
      this.intervalId = null;
      if (this.startTime) {
        this.totalSpentTime += Date.now() - this.startTime;
        this.startTime = null;
      }
    }
  }

  getInitialCountdownValue() {
    return this.initialCountdownValue;
  }

  resumeCountdown() {
    this.startCountdown(this.countdownValue / 1000 / 60); // convert milliseconds back to minutes
  }

  resetCountdown() {
    this.startTime = null;
    this.totalSpentTime = 0;
    this.countdownValue = 0;
    if (this.intervalId) {
      clearInterval(this.intervalId);
      this.intervalId = null;
    }
    this.timeLeft.next(0);
  }

  getTimeSpent() {
    return this.totalSpentTime + (this.startTime ? Date.now() - this.startTime : 0);
  }

  getTimeLeft() {
    return this.countdownValue;
  }

  getTime() {
    return this.timeLeft.asObservable();
  }
}
