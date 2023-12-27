import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { Router } from '@angular/router';
import { CustomMatCardComponent } from './custom-mat-card.component';

describe('CustomMatCardComponent', () => {
  let component: CustomMatCardComponent;
  let fixture: ComponentFixture<CustomMatCardComponent>;

  beforeEach(() => {
    const routerStub = { navigate: () => {} };
    TestBed.configureTestingModule({
      schemas: [NO_ERRORS_SCHEMA],
      declarations: [CustomMatCardComponent],
      providers: [{ provide: Router, useValue: routerStub }]
    });
    fixture = TestBed.createComponent(CustomMatCardComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should have default value of isClicked as false', () => {
    expect(component.isClicked).toEqual(false);
  });
});
